const avatarDoAgente = "/assets/img/logo-agente.png";
const estiloDoAgente = "/assets/css/agente.css";
const corPadraoDoAgente = "#7c3aed";
const idDoBotaoFlutuante = "fab-root";

function corDaMarca(): string {
    const cor = getComputedStyle(document.documentElement)
        .getPropertyValue("--primary-600")
        .trim();

    return cor !== "" ? cor : corPadraoDoAgente;
}

function temaDoAgente(): "light" | "dark" {
    return document.body.classList.contains("tema-claro") ? "light" : "dark";
}

function personalizarAgente(): boolean {
    const webchat = window.botpress;
    const hospedeiro = document.getElementById(idDoBotaoFlutuante);
    const raiz = hospedeiro !== null ? hospedeiro.shadowRoot : null;

    if (!webchat || raiz === null) {
        return false;
    }

    if (raiz.querySelector("link[data-estilo-agente]") === null) {
        const folha = document.createElement("link");
        folha.rel = "stylesheet";
        folha.href = estiloDoAgente;
        folha.setAttribute("data-estilo-agente", "");
        raiz.appendChild(folha);
    }

    webchat.config({
        configuration: {
            color: corDaMarca(),
            themeMode: temaDoAgente(),
            botAvatar: avatarDoAgente,
            welcomeHeading: "Olá! Como posso ajudar você?",
            welcomeSubtitle: "Escolha um assunto ou pergunte do seu jeito.",
        },
    });

    return true;
}

function aguardarBotaoDoAgente(): void {
    if (personalizarAgente()) {
        return;
    }

    const inicio = Date.now();

    const tentativa = window.setInterval(() => {
        if (personalizarAgente() || Date.now() - inicio > 30000) {
            window.clearInterval(tentativa);
        }
    }, 100);
}

aguardarBotaoDoAgente();
