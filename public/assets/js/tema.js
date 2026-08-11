let tema = localStorage.getItem("tema");

if (tema === "claro") {
    document.body.classList.add("tema-claro");
}

document.querySelector("[data-tema-toggle]").addEventListener("click", () => {
    document.body.classList.toggle("tema-claro");

    if (document.body.classList.contains("tema-claro")) {
        localStorage.setItem("tema", "claro");
    } else {
        localStorage.setItem("tema", "escuro");
    }
});