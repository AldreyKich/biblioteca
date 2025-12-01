<!-- Botão Voltar ao Início no topo -->
<div class="d-flex justify-content-start align-items-center mb-3">
    <a href="index.php" class="btn btn-secondary">Voltar</a>
</div>

<canvas id="graficoMensal"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoMensal').getContext('2d');
new Chart(ctx, {
type: 'line',
data: {
labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
datasets: [{
label: 'Empréstimos',
data: [12, 19, 3, 5, 2, 3],
borderColor: 'rgb(102, 126, 234)',
backgroundColor: 'rgba(102, 126, 234, 0.1)',
tension: 0.4
}]
},
options: {
responsive: true,
plugins: {
legend: { display: true }
}
}
});
</script>