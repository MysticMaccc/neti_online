<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{env('SECURE_HEADER_DESCRIPTION')}}">
<meta name="keywords" content="{{env('SECURE_HEADER_KEYWORDS')}}">
<meta name="author" content="NETI">

<script>
    if (localStorage.theme) document.documentElement.setAttribute("data-theme", localStorage.theme);
</script>

{{-- <link rel="preload" as="image" href="assets/videos/landing_page_poster-dark.jpg"> --}}

<style>.animated-text{opacity:0;transform:translateX(-20px);animation:revealText 1.5s forwards ease-out}@keyframes revealText{to{opacity:1;transform:translateX(0)}}.card-hover{position:relative;overflow:hidden}.card-hover::before{content:"";position:absolute;top:0;left:0;width:100%;height:100%;background-size:cover;background-position:center;background-repeat:no-repeat;transition:transform 0.3s ease;z-index:-1}.card-hover:hover::before{transform:scale(1.1)}.gradient-background1{position:absolute;top:0;right:0;bottom:0;left:0;background:linear-gradient(to left,transparent,rgba(0,0,128,0.5));z-index:-1;pointer-events:none}.py-md-20::before{content:"";position:absolute;top:0;right:0;bottom:0;left:0;background:linear-gradient(to left,transparent,rgba(0,0,128,0.5));z-index:-1;pointer-events:none}.contact-section{position:relative;background-image:url('{{asset('assets/images/oesximg/landing-4-bg.jpg')}}');background-size:cover;background-position:center;background-repeat:no-repeat}.overlay{position:absolute;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}.contact-content{position:relative;z-index:2;color:white}</style>

<title>OESX - Online Enrollment System X</title>
@livewireStyles()