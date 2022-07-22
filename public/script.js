
       var modal  = document.getElementByCl('#mon-modal');
       var modalButton = document.querySelector('#modal-open');
       var closeButton = document.getElementById('close');


       modalButton.addEventListener('click', openModal);
       closeButton.addEventListener('click', closeModal);
       
       function openModal() {
        modal.style.display = 'block';
       }

       function closeModal() {
      modal.style.display = 'none';
       }