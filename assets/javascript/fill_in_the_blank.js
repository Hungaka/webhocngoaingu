var boxs = document.querySelectorAll('.wrap__item');
var targers = document.querySelectorAll('.target');

var currentTarget = null;

targers.forEach(targer => {
    targer.addEventListener('dragstart', function() {
        currentTarget = this;
    })
})

boxs.forEach(box => {
    box.addEventListener('dragover', function(e) {
        e.preventDefault();
    })
    box.addEventListener('drop', function(e) {
        if (!box.querySelector('.target')) {
            this.append(currentTarget);
        }
    })
})