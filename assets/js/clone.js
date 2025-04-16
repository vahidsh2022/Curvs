const addFieldButton = document.getElementById('addFieldButton')

if(addFieldButton) {
    addFieldButton.addEventListener('click', function() {
        const container = document.getElementById('socialAccountsContainer');

        const networks = __networkTypes;


        const newFieldContainer = document.createElement('div');
        newFieldContainer.classList.add('new-field');


        const fieldsHTML = `
        <div class="form-group">
            <label for="networkType">Network Type</label>
            <select class="form-control" name="new_data[${container.children.length + 1}][type]">
                ${networks.map((item) => `<option value="${item.value}">${item.label}</option>`).join('')}
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="new_data[${container.children.length + 1}][username]" placeholder="Enter Username">
        </div>
        <div class="form-group">
            <label for="accountPassword">Account Password</label>
            <input type="password" class="form-control" name="new_data[${container.children.length + 1}][password]" placeholder="Enter Password">
        </div>
        <div class="form-group">
            <label for="emailAddress">Email Address</label>
            <input type="email" class="form-control" name="new_data[${container.children.length + 1}][email]" placeholder="Enter Email Address">
        </div>
        <div class="form-group">
            <label for="emailPassword">Email Password</label>
            <input type="password" class="form-control" name="new_data[${container.children.length + 1}][email_password]" placeholder="Enter Email Password">
        </div>
    `;

        newFieldContainer.innerHTML = fieldsHTML;
        container.appendChild(newFieldContainer);
    });
}
