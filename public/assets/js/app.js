"use strict";
const caminho_api = '/api/servicos';
const caminho_dashboard = '/api/dashboard?limite=100';
const total_ranking_exibido = 4;

async function buscarServicos() {
    const resposta = await fetch(caminho_api);
    if (!resposta.ok) {
        throw new Error(`Erro na requisição de serviços: status ${resposta.status}`);
    }
    return await resposta.json();
}

async function buscarDashboard() {
    const resposta = await fetch(caminho_dashboard);
    if (!resposta.ok) {
        throw new Error(`Erro na requisição do dashboard: status ${resposta.status}`);
    }
    return await resposta.json();
}


function somarPrecos(servicos) {
    return servicos.reduce((acumulador, servico) => acumulador + servico.preco, 0);
}


function calcularMediaPrecos(servicos) {
    if (servicos.length === 0) {
        return 0;
    }
    return somarPrecos(servicos) / servicos.length;
}


function encontrarServicoMaisCaro(servicos) {
    if (servicos.length === 0) {
        return null;
    }
    return servicos.reduce((maisCaro, servico) => {
        return servico.preco > maisCaro.preco ? servico : maisCaro;
    });
}


function filtrarServicosPremium(servicos) {
    return servicos.filter((servico) => servico.preco > 300);
}


function filtrarAgendamentosAtivos(agendamentos) {
    return agendamentos.filter((agendamento) => agendamento.status !== 'cancelado');
}


function calcularFaturamentoPrevisto(ranking) {
    return ranking.reduce((total, servico) => {
        return total + servico.total_agendamentos * servico.preco_servico;
    }, 0);
}


function calcularFaturamentoRealizado(ranking) {
    return ranking.reduce((total, servico) => {
        return total + servico.total_concluidos * servico.preco_servico;
    }, 0);
}


function contarAgendamentosPorServico(agendamentos) {
    const contagem = {};
    for (const agendamento of agendamentos) {
        const nome = agendamento.nome_servico;
        contagem[nome] = (contagem[nome] ?? 0) + 1;
    }
    return contagem;
}


function encontrarServicoMaisAgendado(contagem) {
    let nomeDestaque = '';
    let maiorTotal = 0;
    for (const nome of Object.keys(contagem)) {
        const total = contagem[nome] ?? 0;
        if (total > maiorTotal) {
            maiorTotal = total;
            nomeDestaque = nome;
        }
    }
    return nomeDestaque;
}


function formatarPreco(valor) {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
}


function formatarRanking(ranking) {
    const maiorTotal = ranking.reduce((maior, servico) => {
        return servico.total_agendamentos > maior ? servico.total_agendamentos : maior;
    }, 0);
    return ranking.slice(0, total_ranking_exibido).map((servico, indice) => ({
        posicao: indice + 1,
        nome: servico.nome_servico,
        totalAgendamentos: servico.total_agendamentos,
        precoFormatado: formatarPreco(servico.preco_servico),
        faturamentoFormatado: formatarPreco(servico.total_concluidos * servico.preco_servico),
        larguraBarra: maiorTotal > 0 ? Math.round((servico.total_agendamentos / maiorTotal) * 100) : 0,
    }));
}


function definirTexto(id, texto) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.innerText = texto;
    }
}


function renderizarCardsServicos(servicos) {
    const servicoMaisCaro = encontrarServicoMaisCaro(servicos);
    const servicosPremium = filtrarServicosPremium(servicos);
    definirTexto('total-servicos', servicos.length.toString());
    definirTexto('media-precos', formatarPreco(calcularMediaPrecos(servicos)));
    definirTexto('servico-mais-caro', servicoMaisCaro ? servicoMaisCaro.nome : 'Nenhum serviço registrado');
    definirTexto('total-premium', servicosPremium.length.toString());
}


function renderizarCardsDashboard(dados) {
    const agendamentosAtivos = filtrarAgendamentosAtivos(dados.agendamentos);
    const contagem = contarAgendamentosPorServico(agendamentosAtivos);
    const servicoMaisAgendado = encontrarServicoMaisAgendado(contagem);
    definirTexto('faturamento-previsto', formatarPreco(calcularFaturamentoPrevisto(dados.ranking)));
    definirTexto('faturamento-realizado', formatarPreco(calcularFaturamentoRealizado(dados.ranking)));
    definirTexto('servico-mais-agendado', servicoMaisAgendado !== '' ? servicoMaisAgendado : 'Nenhum dado registrado');
    definirTexto('total-agendamentos', dados.paginacao.total.toString());
}


function renderizarRanking(linhas) {
    const lista = document.getElementById('lista-ranking');
    if (!lista) {
        return;
    }
    lista.innerHTML = '';
    if (linhas.length === 0) {
        const vazio = document.createElement('p');
        vazio.className = 'text-center mb-0';
        vazio.innerText = 'Nenhum dado registrado.';
        lista.appendChild(vazio);
        return;
    }
    for (const linha of linhas) {
        const item = document.createElement('div');
        item.className = 'mb-4';
        const topo = document.createElement('div');
        topo.className = 'd-flex justify-content-between align-items-center mb-2';
        const titulo = document.createElement('div');
        titulo.className = 'd-flex align-items-center gap-2';
        const posicao = document.createElement('span');
        posicao.className = `ranking ranking-${linha.posicao}`;
        posicao.innerText = linha.posicao.toString();
        const nome = document.createElement('strong');
        nome.innerText = linha.nome;
        titulo.appendChild(posicao);
        titulo.appendChild(nome);
        const total = document.createElement('span');
        total.className = 'text-secondary';
        total.innerText = `${linha.totalAgendamentos} agend. · ${linha.faturamentoFormatado}`;
        topo.appendChild(titulo);
        topo.appendChild(total);
        const barra = document.createElement('div');
        barra.className = 'progress progress-custom';
        const preenchimento = document.createElement('div');
        preenchimento.className = `progress-bar barra-${linha.posicao}`;
        preenchimento.style.width = `${linha.larguraBarra}%`;
        barra.appendChild(preenchimento);
        item.appendChild(topo);
        item.appendChild(barra);
        lista.appendChild(item);
    }
}


function mostrarMensagem(texto) {
    const mensagem = document.getElementById('mensagem-dashboard');
    if (mensagem) {
        mensagem.innerText = texto;
        mensagem.classList.remove('d-none');
    }
}


function mostrarSemDados() {
    mostrarMensagem('Nenhum dado registrado.');
    definirTexto('total-servicos', '0');
    definirTexto('media-precos', formatarPreco(0));
    definirTexto('servico-mais-caro', 'Nenhum serviço registrado');
    definirTexto('total-premium', '0');
    definirTexto('faturamento-previsto', formatarPreco(0));
    definirTexto('faturamento-realizado', formatarPreco(0));
    definirTexto('servico-mais-agendado', 'Nenhum dado registrado');
    definirTexto('total-agendamentos', '0');
    renderizarRanking([]);
}
async function carregarDashboard() {
    try {
        const [servicos, dados] = await Promise.all([
            buscarServicos(),
            buscarDashboard(),
        ]);
        if (servicos.length === 0 && dados.ranking.length === 0) {
            mostrarSemDados();
            return;
        }
        renderizarCardsServicos(servicos);
        renderizarCardsDashboard(dados);
        renderizarRanking(formatarRanking(dados.ranking));
    }
    catch (erro) {
        console.error('Falha ao carregar os dados da dashboard:', erro);
        mostrarSemDados();
        mostrarMensagem('Não foi possível carregar os dados. Verifique se o servidor e o banco estão no ar.');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
