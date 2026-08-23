const caminho_api = '/api/servicos';

async function carregarDashboard(): Promise<void> {
    try {
        const resposta = await fetch(caminho_api);

        if (!resposta.ok) {
            throw new Error(`Erro na requisição: Status ${resposta.status}`);
        }

        const servicos: Servico[] = await resposta.json();

        atualizarCards(servicos);
    } catch (erro) {
        console.error('Falha ao carregar serviços:', erro);
        alert('Erro ao carregar os dados da API PHP. Verifique o console.');
        mostrarSemDados();
    }
}

function atualizarCards(servicos: Servico[]): void {
    if (!Array.isArray(servicos) || servicos.length === 0) {
        mostrarSemDados();
        return;
    }

    const totalServicos: number = servicos.length;

    const somaPrecos: number = servicos.reduce((acumulador, item) => {
        const preco = Number(item.preco);
        return acumulador + (Number.isFinite(preco) ? preco : 0);
    }, 0);

    const mediaPrecos: number = somaPrecos / totalServicos;

    const servicoMaisCaro: Servico = servicos.reduce((maior, item) => {
        return Number(item.preco) > Number(maior.preco) ? item : maior;
    }, servicos[0]);

    const servicosPremium = servicos.filter((servico) => Number(servico.preco) > 300);

    definirTexto('total-servicos', totalServicos.toString());
    definirTexto('media-precos', formatarPreco(mediaPrecos));
    definirTexto('servico-mais-caro', servicoMaisCaro.nome);
    definirTexto('total-premium', servicosPremium.length.toString());
}

function mostrarSemDados(): void {
    const mensagem = document.getElementById('mensagem-dashboard');

    if (mensagem) {
        mensagem.classList.remove('d-none');
    }

    definirTexto('total-servicos', '0');
    definirTexto('media-precos', formatarPreco(0));
    definirTexto('servico-mais-caro', 'Nenhum serviço registrado');
    definirTexto('total-premium', '0');
}

function formatarPreco(valor: number): string {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function definirTexto(id: string, texto: string): void {
    const elemento = document.getElementById(id);

    if (elemento) {
        elemento.innerText = texto;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
