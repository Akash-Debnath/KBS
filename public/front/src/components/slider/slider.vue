<template>
    <div class="modal fade BannerSliderModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close bannerCloseBtn" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" /></button>
                <div class="modal-body overflow-hidden">
                    <div class="banner-modal">
                        <ul class="bo-slider">
                            <li v-for="a_banner in all_banners" :key="a_banner.id" :data-url="a_banner.media[0].url" :data-title="a_banner.title" :data-type="(a_banner.media[0].url).match(/\.(jpeg|jpg|gif|png)$/) != null ? 'image' : 'video'">
                                <!--                <span>{{a_banner.title}}</span>-->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    var slider;

    var defaultOpt = {
        slideShow: false,
        interval: 4000,
        animation: "slide"
    }

    // Plugin definition.
    $.fn.boSlider = function( options ) {
        // Merging options
        options = $.extend({}, defaultOpt, options);

        // Initializing slider
        initSlider(options, this);

        // Adding click events
        addEvents();

        // Autoplay if true
        if (options.slideShow == true) {
            setTimeout(slider.autoPlay.bind(slider), options.interval);
        }

        return $(".bo-slider");
    };

    // Function responsibe for slider generation
    function initSlider(options, element) {
        // Scraping main information about our slider
        var slides = [];
        $(element[0]).children().each(function() {
            var li = $( this );
            var obj = {};

            obj['data-url'] = li.attr("data-url");
            obj['data-type'] = li.attr("data-type");
            obj['data-title'] = li.attr("data-title");

            slides.push(obj);
        });

        // Replacing the <ul> with the newly generated slider
        $(element[0]).replaceWith(function () {
            var i;
            var elements = "";
            for (i = 0; i < slides.length; i++) {
                var active = i == 0 ? 'active' : '';
                if (slides[i]['data-type'] == "image") { // Template for images
                    elements += "<div class='bo-slide " + options.animation + " " + active +"' title='"+ slides[i]['data-title'] + "'>\n";
                    elements += "<img src='"+ slides[i]['data-url'] + "' alt='" + slides[i]['data-type'] +"'>\n";
                    elements += "<h4>"+slides[i]['data-title']+"</h4>";
                    elements += "</div>\n";
                } else if (slides[i]['data-type'] == "video") { // Template for videos
                    elements += "<div class='bo-slide "+ options.animation + " " + active +"' title='"+ slides[i]['data-title'] + "'>\n";
                    elements += "<video width='100%' height='300px'>\n";
                    elements += "<source src='" + slides[i]['data-url'] + "'>\n"
                    elements += "</video>\n";
                    elements += "<span class='play-button'>&#9654;</span>";
                    elements += "<h4>"+slides[i]['data-title']+"</h4>";
                    elements += "</div>\n";
                }
            }
            elements += "<a class='bo-prev'>&#10094;</a>\n"; // Previous button
            elements += "<a class='bo-next'>&#10095;</a>\n"; // Next button

            var list = "<div class='bo-slider'>" + elements + '</div>\n';
            // Navigation dots
            list += "<div class='bo-dots'>\n";
            for (i = 0; i < slides.length; i++) {
                active = i == 0 ? 'selected' : '';
                list += "<span class='bo-dot " + active + "' data-bo-dot-index='"+ i +"'></span>\n"
            }
            list += "</div>";
            return list;
        });

        // Creating new Slider object, which will contain main information about our slider
        slides.forEach(function (slide, index) {
            slide.ref = $($('.bo-slider')[0]).children()[index];
        });
        slider = new Slider(options, slides);
    }

    function addEvents() {
        $('.bo-dots').children().each(function (index, value) {
            $(value).click(function () {
                var n = $(this).attr('data-bo-dot-index');
                n *= 1;
                slider.active = n;
                slider.showSlide();
            });
        });
        $('.bo-prev').click(function () {
            slider.active--;
            slider.showSlide();
        });
        $('.bo-next').click(function () {
            slider.active++;
            slider.showSlide();
        });
        slider.slides.forEach(function (value, index) {
            if (value['data-type'] == 'video') {
                $($(value['ref']).find(".play-button")[0]).click(function () {
                    slider.resumeVideo(index);
                });
            }
        });
    }

    // Defining Slider class
    function Slider(options, slides) {
        this.active = 0;
        this.options = options;
        this.slides = slides;
        this.playing = false;

        this.showSlide = function () {
            var i;
            var dots = $('.bo-dots').children();
            if (this.active > this.slides.length-1) {
                this.active = 0;
            }
            if (this.active < 0) {this.active = this.slides.length-1}
            for (i = 0; i < this.slides.length; i++) {
                if (this.slides[i]['data-type'] == "video") {
                    $(this.slides[i]['ref']).find("video")[0].pause();
                    $(this.slides[i]['ref']).find("video")[0].currentTime = 0;
                    $(this.slides[i]['ref']).find(".play-button").css("display", "block");
                }
                $(this.slides[i]['ref']).removeClass("active");
            }
            dots.each(function (index, value) {
                $(value).removeClass("selected");
            });

            $(this.slides[this.active]['ref']).addClass('active');
            $(dots[this.active]).addClass('selected');
        }

        this.autoPlay = function () {
            if (this.options.slideShow == true && this.playing == false) {
                this.active++;
                this.showSlide();
                setTimeout(this.autoPlay.bind(this), this.options.interval);
            }
        }

        this.resumeVideo = function (n) {
            var video = $(this.slides[n]['ref']).find("video")[0];
            var button = $($(this.slides[n]['ref']).find(".play-button")[0]);
            video.play();
            button.css("display", "none");
            this.playing = true;
            var that = this;
            $(video).click(function () {
                video.pause();
                button.css("display", "block");
                $(video).unbind("click");
                that.playing = false;
                setTimeout(that.autoPlay.bind(that), options.interval);
            });
            $(video).bind("ended", function() {
                video.currentTime = 0;
                button.css("display", "block");
                $(video).unbind("ended");
                that.playing = false;
                setTimeout(that.autoPlay.bind(that), options.interval);
            });
        }
    }
    import './slider.min.css'

export default {
    props: ['banner_list'],
    data(){
        return {
            all_banners : ''
        }
    },
    mounted() {
        $('.bo-slider').boSlider({
            slideShow: true,
            interval: 6e3,
            animation: "slide"
        });

        // setTimeout(function(){
        //     $('.BannerSliderModal').modal({
        //         show: true,
        //         backdrop: 'static',
        //         keyboard: false
        //     });
        // }, 3000)


        $('.bannerCloseBtn').on('click', () => {
            $('.BannerSliderModal').modal('hide');
        })
    },
    created() {
        $('.bo-slider').boSlider({
            slideShow: true,
            interval: 6e3,
            animation: "slide"
        });
        this.all_banners = this.banner_list;
        console.log(this.all_banners);

        if (this.banner_list.length > 0) {
            setTimeout(function(){
                $('.BannerSliderModal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            }, 1e3)


            $('.bannerCloseBtn').on('click', () => {
                $('.BannerSliderModal').modal('hide');
            })
        }
    }
}
</script>

<style scoped>
    .BannerSliderModal .btn-close {
        position: absolute;
        top: -15px;
        right: -15px;
        height: 30px;
        width: 30px;
        background: #fff;
        color: #000;
        z-index: 1;
        border-radius: 50rem;
        border: 0;
    }

    .BannerSliderModal .modal-body {
        min-height: 330px;
    }
</style>
