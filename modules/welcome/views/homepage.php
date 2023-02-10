<h1>Welcome To <?=WEBSITE_NAME?></h1>
<p>Let's blame all of the bad news on a gang of creepy clowns.</p>
<form>
    <?php
        echo form_label('Original Headline');
        $attr['placeholder'] = 'Enter headline here';
        $attr['id'] = 'original-headline';
        $attr['autocomplete'] = 'off';
        echo form_input('original_headline', '', $attr);
        $btn_attr['onclick'] = 'fetchFunnyHeadline()';
        echo form_button('submit', 'Submit', $btn_attr);
    ?>
</form>
<div class="spinner" style="display: none;"></div>
<div class="story">
    <h2 class="text-center"></h2>
</div>
<style>
    form{
        max-width: 420px;
        margin: 0 auto;
    }

    .spinner{
        min-height: 7em;
    }

    h2{
        text-transform: uppercase;
    }

    #info-div{
        margin: 3em;
    }
</style>

<script>
    const origHeadlineE1 = document.getElementById('original-headline');
    const theForm = document.getElementsByTagName('form')[0];
    const spinner = document.getElementsByClassName('spinner')[0];
    const funnyHeadlineE1 = document.getElementsByTagName('h2')[0];
    const storyE1 = document.getElementById('story');

    function fetchFunnyHeadline(params) {
        if(origHeadlineE1.value !== ''){
            alert(origHeadlineE1.value);

            //remove the info-div, if it exist
            const infoDiv = document.getElementById('info-div');
            if(infoDIV){
                info.remove();
            }

            //hide the form
            theForm.style.display = 'none';

            //display yhe spinner
            spinner.style.display = 'flex';

            //fetch a funny headline from the API endpoint
            const targetUrl = '<?=BASE_URL?>api/create/storiess';

            const params = {
                original_headline: origHeadlineE1.value
            };

            const http = new XMLHttpRequest();
            http.open('post', targetUrl);
            http.setRequestHeader('Content-Type', 'application/json');
            http.send(JSON.stringify(params));
            http.onload = function() {

                if(http.status !== 200) {
                    handleError(http.responseText);
                } else {
                    drawFunnyHeadline(http.responseText);
                }
            }

           /* 
            setTimeout(() => {
                drawFunnyHeadline();
            }, 2000); 
            */
        }
    }

    function handleError(errorMsg) {
        alert(errorMsg);
        //hide the form
        theForm.style.display = 'block';

        //hiden yhe spinner
        spinner.style.display = 'none';

        //clear the form field
        origHeadlineE1.value = '';
    }

    function drawFunnyHeadline(jsonStr){

        const textObj = JSON.parse(jsonStr);

        funnyHeadlineE1.innerHTML = textObj.funny_headline;

        //hide the form
        theForm.style.display = 'block';

        //hiden yhe spinner
        spinner.style.display = 'none';

        //generate Image
        generateImage(origHeadlineE1.value, textObj.id);

        //clear the form field
        origHeadlineE1.value = '';
    }

    function generateImage(headline, updateId){
        
        //create an inof div
        const infoDiv = document.createElement('div');
        infoDiv.setAttribute('id', 'info-div');
        infoDiv.setAttribute('class', 'text-center blink');
        infoDiv.innerHTML = '* LOADING PICTURE - PLEASE WAIT *';

        storyE1.insertBefore(infoDiv, funnyHeadlineE1);

        //send the headline and updateId to our custom API
        const targetUrl = '<?= BASE_URL ?>storiess/init_gen_image';

        //build an obj containing all the things we want to post
        const params = {
            updateId,
            headline
        }

        //create the HTTP post request
        const http = new XMLHttpRequest();
        http.open('post', targetUrl);
        http.setRequestHeader('Content-Type', 'application/json');
        http.send(JSON.stringify(params));
        http.onload = function() {

            if(http.status !== 200) {
                handleError(http.responseText);
            } else {
                drawImage(http.responseText);
            }
        }
    }

    function drawImage(picPath){
        //clear the info div
        const infoDiv = document.getElementById('info-div');
        infoDiv.innerHTML = '';
        infoDiv.ClassList.remove('blink');

        //create an img on the page
        const newPic = document.createElement('img');
        newPic.setAttribute('src', picPath);

        infoDiv.appendChild(newPic);
    }
</script>