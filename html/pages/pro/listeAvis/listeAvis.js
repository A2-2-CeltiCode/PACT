document.addEventListener("DOMContentLoaded", function () {
  // ...existing code...

  function initButtons() {
    // ...existing code...

    const thumbsUpButtons = document.querySelectorAll(".thumbs-up");
    const thumbsDownButtons = document.querySelectorAll(".thumbs-down");

    // ...existing code...

    // Réinitialiser l'observateur pour marquer les avis comme vus
    const avisElements = document.querySelectorAll(".avi.non-vu");
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const idAvis = entry.target.dataset.idavis;
          fetch(`markAsSeen.php?idAvis=${idAvis}`, {
            method: 'POST'
          }).then(response => {
            // ...existing code...
          });
        }
      });
    });

    avisElements.forEach(avi => {
      observer.observe(avi);
    });
  }

  // ...existing code...
});
