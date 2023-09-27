document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.querySelector(".wrapper"),
      carousel = document.querySelector(".carousel"),
      images = document.querySelectorAll("img"),
      buttons = document.querySelectorAll(".button");

console.log(wrapper,carousel,images,buttons);

let imageIndex = 1 , IntervalID ;

const autoSlide = ()=>{
    IntervalID = setInterval(() => slideImage(++imageIndex),2000);
};

autoSlide();

const slideImage = ()=>{
    imageIndex = imageIndex === images.length ? 0 : imageIndex < 0 ? images.length - 1 : imageIndex ;
    carousel.style.transform = `translate(-${imageIndex * 100}%)`;
};

const updateClick = (e) =>{
    clearInterval(IntervalID);
    imageIndex += e.target.id === "next" ? 1 : -1 ;
    slideImage(imageIndex);
    autoSlide();

    // console.log(imageIndex);
}

buttons.forEach(button =>button.addEventListener("click",updateClick));
wrapper.addEventListener("mouseover",()=> clearInterval(IntervalID));
wrapper.addEventListener("mouseleave",autoSlide);
  });