<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\FaturaCriadaMail;
use App\Notifications\NovaFaturaNotification;
use Illuminate\Support\Facades\Notification;

class FaturaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Fatura::where('user_id', Auth::id());

            // Aplica ordenação com base no parâmetro da query string
            switch ($request->input('sort')) {
                case 'fornecedor_asc':
                    $query->orderBy('fornecedor', 'asc');
                    break;
                case 'fornecedor_desc':
                    $query->orderBy('fornecedor', 'desc');
                    break;
                case 'data_asc':
                    $query->orderBy('data', 'asc');
                    break;
                case 'data_desc':
                    $query->orderBy('data', 'desc');
                    break;
                case 'valor_asc':
                    $query->orderBy('valor', 'asc');
                    break;
                case 'valor_desc':
                    $query->orderBy('valor', 'desc');
                    break;
                default:
                    $query->orderBy('data', 'desc');
            }

            $faturas = $query->paginate(10)->withQueryString();

        } catch (\Exception $e) {
            $faturas = collect([]);
            return view('faturas.index', compact('faturas'))
                ->with('error', 'Erro ao carregar faturas: Estrutura da tabela pode precisar de atualização.');
        }

        return view('faturas.index', compact('faturas'));
    }

    public function create()
    {
        return view('faturas.create');
    }

    public function store(Request $request)
    {
        // Validação para os dados do formulário
        $validated = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'nif' => 'nullable|string|max:20',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|string',
            'imagem_upload' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'email_para' => 'nullable|email',
            'telefone_para' => 'nullable|string',
            'telefone' => 'nullable|string', // Campo adicional do formulário
            'enviar_email' => 'nullable|boolean',
            'enviar_sms' => 'nullable|boolean',
        ]);

        try {
            $fatura = new Fatura();
            $fatura->user_id = Auth::id();
            $fatura->fornecedor = $validated['fornecedor'];
            $fatura->nif = $validated['nif'] ?? null;
            $fatura->data = $validated['data'];
            $fatura->valor = $validated['valor'];

            // Imagem base64
            if ($request->has('imagem') && !empty($validated['imagem'])) {
                $imageData = str_replace('data:image/png;base64,', '', $validated['imagem']);
                $imageData = base64_decode($imageData);
                $imageName = 'fatura_' . time() . '.png';
                Storage::disk('public')->put('faturas/' . $imageName, $imageData);
                $fatura->imagem = 'faturas/' . $imageName;
            }

            // Upload de imagem
            if ($request->hasFile('imagem_upload')) {
                $file = $request->file('imagem_upload');
                $path = $file->store('faturas', 'public');
                $fatura->imagem = $path;
            }

            $fatura->save();

            $messages = ['Fatura registrada com sucesso!'];

            // ENVIO DE E-MAIL (opcional)
            if (!empty($validated['enviar_email']) && $validated['enviar_email'] == true && !empty($validated['email_para'])) {
                if (filter_var($validated['email_para'], FILTER_VALIDATE_EMAIL)) {
                    Log::info('Tentando enviar e-mail para: ' . $validated['email_para']);
                    try {
                        Mail::to($validated['email_para'])->send(new FaturaCriadaMail($fatura));
                        Log::info('Email enviado com sucesso para: ' . $validated['email_para']);
                        $messages[] = 'E-mail enviado com sucesso!';
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar email: ' . $e->getMessage());
                        $messages[] = 'Fatura salva, mas erro ao enviar e-mail: ' . $e->getMessage();
                    }
                } else {
                    Log::error('Email inválido informado: ' . $validated['email_para']);
                    $messages[] = 'Fatura salva, mas o e-mail informado é inválido.';
                }
            }

            // ENVIO DE SMS (opcional)
            $telefoneParaSms = $validated['telefone_para'] ?? $validated['telefone'] ?? null;
            
            if (!empty($validated['enviar_sms']) && $validated['enviar_sms'] == true && !empty($telefoneParaSms)) {
                Log::info('Tentando enviar SMS para: ' . $telefoneParaSms);
                try {
                    // Usar o sistema de notificações do Laravel
                    $user = Auth::user();
                    $user->notify(new NovaFaturaNotification($fatura, null, $telefoneParaSms, 'criada'));
                    
                    Log::info('SMS enviado com sucesso para: ' . $telefoneParaSms);
                    $messages[] = 'SMS enviado com sucesso!';
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar SMS: ' . $e->getMessage());
                    $messages[] = 'Fatura salva, mas erro ao enviar SMS: ' . $e->getMessage();
                }
            }

            return redirect()->route('faturas.index')->with('success', implode(' ', $messages));

        } catch (\Exception $e) {
            Log::error('Erro ao salvar fatura', [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ]);

            return back()->withInput()->with('error', 'Erro ao salvar a fatura: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('faturas.index')
                ->with('error', 'ID de fatura inválido.');
        }

        $fatura = Fatura::find($id);

        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada.');
        }

        if ($fatura->user_id != Auth::id()) {
            \Log::warning('Tentativa de acesso não autorizado à fatura #' . $id . ' pelo usuário #' . Auth::id());
            
            return redirect()->route('faturas.index')
                ->with('error', 'Você não tem permissão para visualizar esta fatura.');
        }

        return view('faturas.show', compact('fatura'));
    }

    public function edit($id)
    {
        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);
            return view('faturas.edit', compact('fatura'));
        } catch (\Exception $e) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada ou você não tem permissão para editá-la.');
        }
    }

   public function update(Request $request, $id)
{
    $fatura = Fatura::findOrFail($id);

    $validated = $request->validate([
        'fornecedor' => 'required|string|max:255',
        'nif' => 'nullable|digits:9',
        'data' => 'required|date',
        'valor' => 'required|numeric|min:0.01',
        'imagem' => 'nullable|image|max:2048', // imagem válida e até 2MB
    ]);

    // Upload da nova imagem
    if ($request->hasFile('imagem')) {
        // Remove imagem anterior se existir
        if ($fatura->imagem && Storage::exists('public/' . $fatura->imagem)) {
            Storage::delete('public/' . $fatura->imagem);
        }

        // Guarda nova imagem
        $path = $request->file('imagem')->store('faturas', 'public');
        $validated['imagem'] = $path;
    }

    // Atualiza os dados
    $fatura->update($validated);

     return redirect()->route('faturas.index')->with('success', 'Atualizado com sucesso!');
}


    public function exportPdf()
    {
        $faturas = Fatura::where('user_id', Auth::id())->get();
        $pdf = Pdf::loadView('faturas.pdf', compact('faturas'));
        return $pdf->download('faturas.pdf');
    }

    public function destroy($id)
    {
        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);
            
            if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
                Storage::disk('public')->delete($fatura->imagem);
            }

            $fatura->delete();
            
            return redirect()->route('faturas.index')
                ->with('success', 'Fatura removida com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('faturas.index')
                ->with('error', 'Erro ao remover a fatura: ' . $e->getMessage());
        }
    }
}