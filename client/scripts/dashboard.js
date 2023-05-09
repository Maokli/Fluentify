import axiosInstance from '../helpers/axiosInstanceClient.js';

//a function creating a language card with a progress bar  
function createLanguageCard(language) {
    return `
      <div class="col-md-3 mb-4">
            <div class="card hoverable h-100" style="width: 13rem; height: 10rem;">
                <div class="d-flex justify-content-center">
                 <img src="${language.photo}" class="card-img-top rounded-start flags text-center" alt="${language.languageName} flag">
          </div>
            <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-center mt-3">${language.languageName}</h5>
                    <div class="progress w-100 mx-auto">
                        <div class="progress-bar" role="progressbar" style="width: ${parseInt(language.progress*100)}%;" aria-valuenow="${parseInt(language.progress*100)}" aria-valuemin="0" aria-valuemax="100">${parseInt(language.progress*100)}%</div>
                    </div>
             </div>
      </div>
    `;
  }


axiosInstance.get('/dashboard')
  .then(function (response) {
    //getting languages from the database
    const languages=response.data;
    const cardContainer= document.querySelector('#cardContainer');
    //injecting each card int the card container
    languages.forEach(language=> {
        //creating a card for each language
      const card = createLanguageCard(language);
      const cardElement = document.createRange().createContextualFragment(card).firstElementChild;
      cardContainer.appendChild(cardElement);
    });
    
  })
  .catch(function (error) {
    console.log(error);
  });
 