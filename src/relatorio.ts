const caminho_dashboard_relatorio = '/api/dashboard?limite=100';
const total_ranking_relatorio = 4;

const meses_abreviados = [
    'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
    'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez',
];

const cores_ranking = ['#7c3aed', '#34d399', '#fbbf24', '#e879f9'];

async function buscarDadosRelatorio(): Promise<RespostaDashboard | null> {
    try {
        const resposta = await fetch(caminho_dashboard_relatorio);

        if (!resposta.ok) {
            return null;
        }

        return await resposta.json() as RespostaDashboard;
    } catch (erro) {
        return null;
    }
}

function contarAgendamentosPorMes(agendamentos: AgendamentoResumo[]): Record<string, number> {
    return agendamentos.reduce((contagem: Record<string, number>, agendamento) => {
        const mes = agendamento.data_hora_servico.slice(0, 7);

        contagem[mes] = (contagem[mes] ?? 0) + 1;

        return contagem;
    }, {});
}

function formatarRotuloDoMes(mes: string): string {
    const [ano, numero] = mes.split('-');

    return `${meses_abreviados[Number(numero) - 1]}/${ano.slice(2)}`;
}

function montarSerieDeMeses(agendamentos: AgendamentoResumo[]): SerieDoGrafico {
    const contagem = contarAgendamentosPorMes(agendamentos);
    const meses = Object.keys(contagem).sort();

    return {
        rotulos: meses.map(formatarRotuloDoMes),
        valores: meses.map((mes) => contagem[mes]),
    };
}

function montarSerieDoRanking(ranking: ServicoRanking[]): SerieDoGrafico {
    const primeiros = ranking.slice(0, total_ranking_relatorio);

    return {
        rotulos: primeiros.map((servico) => servico.nome_servico),
        valores: primeiros.map((servico) => servico.total_agendamentos),
    };
}

function desenharGrafico(id: string, configuracao: object): void {
    const canvas = document.getElementById(id) as HTMLCanvasElement | null;

    if (!canvas) {
        return;
    }

    const contexto = canvas.getContext('2d');

    if (contexto) {
        new Chart(contexto, configuracao);
    }
}

function desenharGraficoDeAgendamentos(serie: SerieDoGrafico): void {
    desenharGrafico('graficoAgendamentos', {
        type: 'line',
        data: {
            labels: serie.rotulos,
            datasets: [{
                label: 'Agendamentos',
                data: serie.valores,
                borderColor: '#a78bfa',
                backgroundColor: 'rgba(124, 58, 237, 0.18)',
                fill: true,
                tension: 0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', display: true },
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.08)' } },
                x: { grid: { color: 'rgba(255, 255, 255, 0.08)' } },
            },
        },
    });
}

function desenharGraficoDeServicos(serie: SerieDoGrafico): void {
    desenharGrafico('graficoServicos', {
        type: 'doughnut',
        data: {
            labels: serie.rotulos,
            datasets: [{
                data: serie.valores,
                backgroundColor: cores_ranking,
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            cutout: '70%',
        },
    });
}

function lerTextoDoElemento(id: string): string {
    return document.getElementById(id)?.textContent?.trim() ?? '—';
}

function montarBarrasDoPdf(serie: SerieDoGrafico): string {
    const maiorValor = serie.valores[0] ?? 0;

    return serie.rotulos.map((nome, posicao) => {
        const total = serie.valores[posicao];
        const largura = maiorValor > 0 ? Math.round((total / maiorValor) * 100) : 0;

        return `
        <div style="margin-bottom: 14px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <span style="font-size: 12px; font-weight: 600;">${posicao + 1}. ${nome}</span>
            <span style="font-size: 12px; color: #888;">${total} agend.</span>
          </div>
          <div style="background: #f0f0f0; border-radius: 99px; height: 7px;">
            <div style="width: ${largura}%; background: ${cores_ranking[posicao]}; height: 7px; border-radius: 99px;"></div>
          </div>
        </div>
      `;
    }).join('');
}

function montarCardsDoPdf(): string {
    const resumo = [
        { rotulo: 'Agendamentos no total', valor: lerTextoDoElemento('resumo-agendamentos') },
        { rotulo: 'Serviços cadastrados', valor: lerTextoDoElemento('resumo-servicos') },
        { rotulo: 'Usuários cadastrados', valor: lerTextoDoElemento('resumo-usuarios') },
        { rotulo: 'Receita total', valor: lerTextoDoElemento('resumo-receita') },
    ];

    return resumo.map((item) => `
        <div style="flex: 1; text-align: center; border: 1px solid #eee; border-radius: 10px; padding: 12px 8px;">
          <div style="font-size: 16px; font-weight: bold; color: #3f2d52;">${item.valor}</div>
          <div style="font-size: 11px; color: #888; margin-top: 4px;">${item.rotulo}</div>
        </div>
      `).join('');
}

function montarPaginaDoPdf(serieRanking: SerieDoGrafico): HTMLDivElement {
    const agora = new Date();
    const dataFormatada = agora.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

    const graficoAgendamentos = document.getElementById('graficoAgendamentos') as HTMLCanvasElement | null;
    const graficoServicos = document.getElementById('graficoServicos') as HTMLCanvasElement | null;

    const imagemAgendamentos = graficoAgendamentos ? graficoAgendamentos.toDataURL('image/png') : '';
    const imagemServicos = graficoServicos ? graficoServicos.toDataURL('image/png') : '';

    const container = document.createElement('div');

    container.innerHTML = `
        <div style="padding: 36px; font-family: sans-serif; background: #fff; color: #1a1a1a;">

          <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #3f2d52; padding-bottom: 16px; margin-bottom: 28px;">
            <div>
              <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px;">Painel Administrativo</h1>
              <p style="font-size: 12px; color: #888; margin: 0;">Visão geral do desempenho do salão</p>
            </div>
            <p style="font-size: 11px; color: #aaa; margin: 0;">Gerado em: ${dataFormatada}</p>
          </div>

          <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 4px;">Agendamentos por mês</h2>
          <p style="font-size: 11px; color: #aaa; margin-bottom: 12px;">Quantidade de agendamentos registrados em cada mês</p>
          <img src="${imagemAgendamentos}" style="width: 100%; border-radius: 8px; margin-bottom: 28px;" />

          <div style="display: flex; gap: 32px; margin-bottom: 28px;">
            <div style="flex: 1;">
              <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 4px;">Serviços Mais Populares</h2>
              <p style="font-size: 11px; color: #aaa; margin-bottom: 14px;">Ranking por número de agendamentos</p>
              ${montarBarrasDoPdf(serieRanking)}
            </div>
            <div style="width: 180px; display: flex; align-items: center;">
              <img src="${imagemServicos}" style="width: 100%;" />
            </div>
          </div>

          <div style="border-top: 1px solid #eee; padding-top: 20px;">
            <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 14px;">Resumo do período</h2>
            <div style="display: flex; gap: 12px;">
              ${montarCardsDoPdf()}
            </div>
          </div>

        </div>
      `;

    return container;
}

function ligarBotaoDeExportar(serieRanking: SerieDoGrafico): void {
    const botao = document.querySelector('.btn-exportar') as HTMLButtonElement | null;

    if (!botao) {
        return;
    }

    botao.addEventListener('click', () => {
        botao.disabled = true;
        botao.innerHTML = 'Gerando... <i class="ph ph-spinner"></i>';

        const opcoes = {
            margin: 0,
            filename: `relatorio-${new Date().toISOString().slice(0, 10)}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        };

        html2pdf()
            .set(opcoes)
            .from(montarPaginaDoPdf(serieRanking))
            .save()
            .then(() => {
                botao.disabled = false;
                botao.innerHTML = 'Exportar Relatório <i class="ph ph-download"></i>';
            });
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    Chart.defaults.color = '#a6a6b3';
    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';

    const dados = await buscarDadosRelatorio();

    if (dados === null) {
        return;
    }

    const serieDeMeses = montarSerieDeMeses(dados.agendamentos);
    const serieDoRanking = montarSerieDoRanking(dados.ranking);

    desenharGraficoDeAgendamentos(serieDeMeses);
    desenharGraficoDeServicos(serieDoRanking);
    ligarBotaoDeExportar(serieDoRanking);
});
