var previousBtnId = 'none'

function removeButtonFromClassOnClicked(btnId){
    if (previousBtnId !== 'none') {
        if ( document.getElementById(previousBtnId).classList.contains('action-btn-selected') ){
            document.getElementById(previousBtnId).classList.remove('action-btn-selected');
            document.getElementById(previousBtnId).classList.add('action-btn');
        }
    }

    if ( document.getElementById(btnId).classList.contains('action-btn') ){
        document.getElementById(btnId).classList.remove('action-btn');
        document.getElementById(btnId).classList.add('action-btn-selected');
        previousBtnId = btnId;
    }
}