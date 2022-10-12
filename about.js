// ---- DOM Traversal 
$("h3").on("click", function() {

    // .slideToggle() takes two arguments
    // 1st arg: the duration of the animation (ms)
    // 2nd arg: defines the function that runs AFTER the animation is finished
    $(this).next().slideToggle(500, function() {
        console.log("slide finished!");
    });
    console.log("next....")
});