fetch('menu.json')
    .then(res => res.json())
    .then(data => {
        const menuDiv = document.getElementById('menu');

        data.forEach(category => {
            const catDiv = document.createElement('div');
            catDiv.classList.add('category');

            const title = document.createElement('h2');
            title.textContent = category.category;
            catDiv.appendChild(title);

            const itemsGrid = document.createElement('div');
            itemsGrid.classList.add('items');

            category.items.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('item');

                itemDiv.innerHTML = `
          <img src="${item.image}" alt="${item.name}">
          <div class="item-content">
            <span>${item.name}</span>
            <span class="price">$${item.price}</span>
          </div>
        `;

                itemsGrid.appendChild(itemDiv);
            });

            catDiv.appendChild(itemsGrid);
            menuDiv.appendChild(catDiv);
        });
    });