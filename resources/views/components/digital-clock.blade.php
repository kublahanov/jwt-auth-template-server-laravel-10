<p class="digital-font my-clocks">
    <span id="clock"><?= date('H:i:s') ?></span>
</p>

<script>
    window.onload = function () {
        window.setInterval(function () {
            const now = new Date();
            const clock = document.getElementById("clock");
            clock.innerHTML = now.toLocaleTimeString();
        }, 1000);
    };
</script>

<link rel="stylesheet" href="https://fonts.cdnfonts.com/css/dseg14-classic">

<style>
    .digital-font {
        font-family: 'DSEG14 Classic', sans-serif;
    }

    .my-clocks {
        margin: 1rem !important;
        padding: 0.7rem;
        border: 1px dashed lightgray;
        border-radius: 5px;
        background-color: white;
    }
</style>
