import axiosInstance from './axiosInstanceClient.js';
//creating a card for each language
function createLanguageCard(language) {
  return `
    <div class="col-md-3 mb-4">
      <div class="card h-100" style="width: 13rem; height: 10rem;">
        <div class="d-flex justify-content-center">
          <img src="${language.photoUrl}" class="card-img-top rounded-start flags text-center" alt="${language.name} flag">
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
          <h5 class="card-title text-center mt-3">${language.name}</h5>
          <div class="d-flex justify-content-between align-items-center">
            <p>${language.description}</p>
          </div>
          <button class="btn btn-primary"><i class="bi bi-plus"></i>Join them !</button>
        </div>
      </div>
    </div>
  `;
}

axiosInstance.get('/languages')
  .then(function (response) {
    //getting languages from the database
    const languages=response.data;
    const cardContainer= document.querySelector('#cardContainer');
    //injecting each card int the card container
    languages.forEach(language=> {
      const card = createLanguageCard(language);
      const cardElement = document.createRange().createContextualFragment(card).firstElementChild;
      cardContainer.appendChild(cardElement);
    });
    
    
  })
  .catch(function (error) {
    console.log(error);
  });