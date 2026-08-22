"use strict";
const caminho_api = '/api/servicos';
async function carregarDashboard() {
    try {
        const resposta = await fetch(caminho_api);
        if (!resposta.ok) {
            throw new Error(`Erro na requisição: Status ${resposta.status}`);
        }
        const servicos = await resposta.json();
        atualizarCards(servicos);
    }
    catch (erro) {
        console.error('Falha ao carregar serviços:', erro);
        alert('Erro ao carregar os dados da API PHP. Verifique o console.');
        definirTexto('total-servicos', '0');
        definirTexto('media-precos', formatarPreco(0));
        definirTexto('servico-mais-caro', '—');
    }
}
function atualizarCards(servicos) {
    if (servicos.length === 0) {
        definirTexto('total-servicos', '0');
        definirTexto('media-precos', formatarPreco(0));
        definirTexto('servico-mais-caro', '—');
        return;
    }
    const totalServicos = servicos.length;
    const somaPrecos = servicos.reduce((acumulador, item) => {
        return acumulador + Number(item.preco);
    }, 0);
    const mediaPrecos = somaPrecos / totalServicos;
    const servicoMaisCaro = servicos.reduce((maior, item) => {
        return Number(item.preco) > Number(maior.preco) ? item : maior;
    }, servicos[0]);
    definirTexto('total-servicos', totalServicos.toString());
    definirTexto('media-precos', formatarPreco(mediaPrecos));
    definirTexto('servico-mais-caro', servicoMaisCaro.nome);
}
function formatarPreco(valor) {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}
function definirTexto(id, texto) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.innerText = texto;
    }
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
