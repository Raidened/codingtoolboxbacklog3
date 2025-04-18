document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[data-search][type="text"]').forEach(function(input) {
        const tableId = input.getAttribute('data-table');
        const columns = (input.getAttribute('data-search') || '1').split(',').map(Number);
        const table = document.getElementById(tableId);
        if (!table) return;
        input.addEventListener('input', function () {
            const filter = input.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                let match = false;
                columns.forEach(colIdx => {
                    const cell = row.querySelector(`td:nth-child(${colIdx})`);
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        match = true;
                    }
                });
                row.style.display = match ? '' : 'none';
            });
        });
    });
});
