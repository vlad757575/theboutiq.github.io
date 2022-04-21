         const open = document.getElementById("open");
         const modal_container = document.getElementById("my-modal_container");
         const close = document.getElementById("close_my-modal");
         const openContact =document.getElementById("open_contact")
         const openSign =document.getElementById("open_sign")
     
         open.addEventListener("click", () => {
           modal_container.classList.add("show");
         });
     
         close.addEventListener("click", () => {
           modal_container.classList.remove("show");
         });

         openContact.addEventListener("click", () => {
          modal_container.classList.add("show");
        });

        openSign.addEventListener("click", () => {
          modal_container.classList.add("show");
        });

        