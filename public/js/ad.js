$('#add-image').click(function(){

    //récuperer le numéro des futurs champs
           const index = +$('#widget-count').val();
    //récupérer le prototype des entrées

           const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g,index);

    //injecter le code dans la div

    $('#annonce_images').append(tmpl);

    //On ajoute 1 à la valeur initiale 
    $('#widget-count').val(index+1);

     deleteButtons();

});

function updateCounter(){

   const count= +$('#annonce_images div.form-group').length;
   //on met à jour la valeur de widget-count

    $('#widget-count').val(count);

}

function deleteButtons(){

    $('button[data-action = "delete"]').click(function(){
            const target = this.dataset.target;
            $(target).remove();

    });
}

updateCounter();
deleteButtons();