type StatusAgendamento = 'pendente' | 'confirmado' | 'cancelado' | 'concluido';

type Servico = {
    id_servico: number;
    preco: number;
    duracao_em_minutos: number;
    nome: string;
    descricao: string;
};


type IndicadoresDashboard = {
    total_hoje: number;
    total_confirmados: number;
    total_pendentes: number;
    receita_hoje: number;
};

type ServicoRanking = {
    id_servico: number;
    nome_servico: string;
    preco_servico: number;
    total_agendamentos: number;
    total_concluidos: number;
};

type AgendamentoResumo = {
    id_agendamento: number;
    nome_cliente: string;
    data_hora_servico: string;
    nome_servico: string;
    status: StatusAgendamento;
};

type Paginacao = {
    total: number;
    limite: number;
    offset: number;
    status: StatusAgendamento | null;
};

type RespostaDashboard = {
    indicadores: IndicadoresDashboard;
    ranking: ServicoRanking[];
    agendamentos: AgendamentoResumo[];
    paginacao: Paginacao;
};

type RespostaErroApi = {
    error: string;
};

type LinhaRankingFormatada = {
    posicao: number;
    nome: string;
    totalAgendamentos: number;
    precoFormatado: string;
    faturamentoFormatado: string;
    larguraBarra: number;
};
type SerieDoGrafico = {
    rotulos: string[];
    valores: number[];
};

declare const Chart: {
    new (contexto: CanvasRenderingContext2D, configuracao: object): unknown;
    defaults: { color: string; borderColor: string };
};

type ExportadorPdf = {
    set(opcoes: object): ExportadorPdf;
    from(elemento: HTMLElement): ExportadorPdf;
    save(): Promise<void>;
};

declare function html2pdf(): ExportadorPdf;
