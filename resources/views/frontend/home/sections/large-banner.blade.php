<section id="wsus__large_banner">
    <div class="container">
        <div class="row">
            <div class="cl-xl-12">
                @if($homepage_section_banner_three->banner_one->status == 1)
                    <a href="{{ $homepage_section_banner_four->banner_one->banner_url }}">
                        <img class="img-fluid" src="{{ asset($homepage_section_banner_four->banner_one->banner_image) }}" alt="">
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
