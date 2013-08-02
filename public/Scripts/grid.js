(function(window) {

    "use strict"

    var $ = window.jQuery;

    function LikeAGoogleImages(pai, filhos, detail){
        this.plugin = 'LikeAGoogleImages';
        this.pai = $(pai);
        this.filhos = this.pai.find(filhos);
        this.detail = detail;
        this.cache = {}
        this.running = false;
        this.init();
    }

    LikeAGoogleImages.prototype.init = function() {
        var that = this;
        this.filhos.on('click.' + this.plugin, function(ev){
            ev.preventDefault();
            ev.stopPropagation();
            var $this = $(this);
            if($this.hasClass('opened')){
                that.hide();
            } else {
                that.hide();
                that.show($this);
            }
        });
    };

    LikeAGoogleImages.prototype.hide = function() {
        var that = this,
            detail;

        this.filhos.filter('.opened').each(function(i, it){
            var el = $(it);
            detail = el.find(that.detail);
            detail.prev().removeClass('seta');
            TweenMax.to(detail, 0.1, {"alpha": 0, onComplete: function(){
                detail.addClass('hidden');
                TweenMax.to(el, 0.5, {"height": el.data('size'), onComplete: function(){
                    el.removeClass('opened');
                }});
            }});
        });
    };

    LikeAGoogleImages.prototype.show = function(el) {
        var detail = el.find(this.detail),
            elSize = el.outerHeight(),
            size;

        if(detail.length > 0){
            el.data('size', elSize);
            size = this.getOuterHeight(detail) + elSize;
            TweenMax.to(el, 0.1, {"height": size, onComplete: function(){
                detail.removeClass('hidden').prev().addClass('seta');
                TweenMax.to(detail, 0.5, {"alpha": 1, onComplete: function(){
                    el.addClass('opened');
                }});
            }});
        }
    };

    LikeAGoogleImages.prototype.getOuterHeight = function(el) {
        el.removeClass('hidden');
        var size = el.outerHeight(true);
        el.addClass('hidden');
        return size;
    };

    window.LikeAGoogleImages = LikeAGoogleImages;


})(window);