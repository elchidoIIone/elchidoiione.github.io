window.onload = () =>{
    let basePrice = 20.00;
    let imgGallery = document.querySelector("#main-product-img");
    let imgs = document.querySelectorAll(".thumb");

    let qtyInput = document.querySelector("#quantity");
    let btnDecrease = document.querySelector("#decrease");
    let btnIncrease = document.querySelector("#increase");

    const currentPrice = document.querySelector("#current-price");
    const oldPrice = document.querySelector("#old-price");
    const discountPrice = document.querySelector("#discount-price");

    const reviewForm = document.querySelector("#review-form");
    const reviewName = document.querySelector("#user-name");
    const reviewComment = document.querySelector("#user-comment");
    const reviewList = document.querySelector("#reviews-list");
//Programe la galería cambiando el atributo SRC de la imagen.
    for (let i = 0; i < imgs.length; i++) {
        imgs[i].addEventListener("click", (event) =>{
            imgGallery.src = event.target.src.replace("thumbs/", "");
            imgs.forEach(item=>{
                if(item !== event.target){
                    item.classList.remove("active");
                }
            });
            event.target.classList.add("active");
        });
    }
    //Programe los botones de "Size" removiendo y agregando la clase "active" y recalcule el precio los de 50ml valen $15.00.
    let btnSize = document.querySelectorAll(".size-btn");
    
    for (let i = 0; i < btnSize.length; i++) {
        btnSize[i].addEventListener("click", (event) =>{
            btnSize.forEach(item=>{
                if(item !== event.target){
                    item.classList.remove("active");
                }
            });
            event.target.classList.add("active");
            basePrice = parseFloat(event.target.dataset.price);
            PriceAndDiscount();
        });
    }

    //Programar los botones de incremento y decremento de las cantidades poniendo como condición máximo 15 y mínimo 1.
    btnDecrease.addEventListener("click", () => {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
            PriceAndDiscount();
        }
    });

    btnIncrease.addEventListener("click", () => {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < 15) {
                qtyInput.value = currentValue + 1;
                PriceAndDiscount();
            }
        });
    qtyInput.addEventListener("change", () => {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue < 1) {
            qtyInput.value = 1;
        } else if (currentValue > 15) {
            qtyInput.value = 15;
        }
        PriceAndDiscount();
    });

    function PriceAndDiscount(){
        let quantity = parseInt(qtyInput.value);
        let discount = 0;
        let discountText = "";

        if (quantity >10){
            discount = 0.20;
            discountText = "20% OFF";
        }
        else if (quantity >5 && quantity <=10){
            discount = 0.10;
            discountText = "10% OFF";
        }
        else{
            discount = 0;
            discountText = "";
        }
        const totalPrice = basePrice * (1 - discount);

        currentPrice.textContent = "$" + totalPrice.toFixed(2);
        oldPrice.textContent = "$" + basePrice.toFixed(2);

        if(discount > 0){
            discountPrice.textContent = discountText;
            discountPrice.style.display = "inline-block";
            oldPrice.style.display = "inline-block";
        }
        else{
            discountPrice.style.display = "none";
            oldPrice.style.display = "none";
        }
    }
    function addStars(rating){
        let html = "";
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating % 1) >= 0.4; 
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        for (let i = 0; i < fullStars; i++) {
            html += '<i class="fas fa-star"></i>';
        }

        if (hasHalfStar) {
            html += '<i class="fas fa-star-half-alt"></i>';
        }
        
        for (let i = 0; i < emptyStars; i++) { 
            html += '<i class="fa-regular fa-star"></i>'; 
        }
        return html;
    }
    function loadReviews(){
        reviewList.innerHTML = "";
        const reviews = JSON.parse(localStorage.getItem("reviews")) || [];
        reviews.forEach(review =>{
            const reviewItem = document.createElement("div");
            reviewItem.classList.add("review");

            const starsHtml = addStars(review.rating);
            reviewItem.innerHTML = `
                <div class="review-header">
                    <strong>${review.name}</strong>
                    <span class="review-rating">${starsHtml} (${review.rating.toFixed(1)})</span>
                </div>
                <p>${review.comment}</p>
            `;
            reviewList.appendChild(reviewItem);
        });
    }
    reviewForm.addEventListener("submit", (event) =>{
        event.preventDefault();
        const random = (Math.random() *4.0) + 1.0;

        const newReview ={
            name: reviewName.value,
            comment: reviewComment.value,
            rating: random
        };
        const reviews = JSON.parse(localStorage.getItem("reviews")) || [];
        reviews.push(newReview);
        localStorage.setItem("reviews", JSON.stringify(reviews));
        reviewName.value = "";
        reviewComment.value = "";
        loadReviews();
    });
    loadReviews();
    PriceAndDiscount();
}
//Nota importante: Use gemini para apoyarme en muchas cosas mas para generar aleatoriamente las calificaciones de las reseñas