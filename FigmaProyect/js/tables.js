document.addEventListener('DOMContentLoaded', function(){
    let container = document.getElementById('table');

    let table = document.createElement('table');
    let tBody = document.createElement('tbody');

    for (let i = 0; i < 10; i++) {
        const row = document.createElement('tr')
        
        for (let j = 0; j < 10; j++) {
            let cells;
            if (i===0 && j ===0){
                cells = document.createElement('th');
                cells.textContent = `Table XD`
            }
            else if(i === 0){
                cells = document.createElement('th');
                cells.textContent = `${j} Column`;
            }
            else if (j===0){
                cells = document.createElement('th');
                cells.textContent =`${i} Row`;
            }
            
            else{
                    cells = document.createElement('td');

                    const numericInput = document.createElement('input');
                    numericInput.type = 'number';
                    numericInput.style.width = '100%';
                    numericInput.style.border = 'none';
                    numericInput.style.textAlign = 'center';

                    cells.appendChild(numericInput);
            }
            row.appendChild(cells)
        }
        tBody.appendChild(row)
    }
    table.appendChild(tBody);
    container.appendChild(table)
})

