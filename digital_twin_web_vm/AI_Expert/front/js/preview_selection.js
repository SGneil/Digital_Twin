document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".step");
    steps.forEach(step => {
        step.addEventListener("mouseover", () => {
            step.style.backgroundColor = "#d4edda";
        });
        step.addEventListener("mouseout", () => {
            step.style.backgroundColor = "#e9ecef";
        });
    });
});
