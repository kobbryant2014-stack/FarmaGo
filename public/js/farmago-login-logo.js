document.addEventListener("DOMContentLoaded", function () {
    if (typeof gsap === "undefined") {
        console.warn("GSAP no esta cargado.");
        return;
    }

    const logo = document.querySelector(".farmago-login-brand");
    const logoImage = document.querySelector(".farmago-login-logo");

    if (!logo) {
        return;
    }

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (prefersReducedMotion) {
        gsap.set(".farmago-login-brand, .farmago-login-logo", {
            clearProps: "all",
            opacity: 1
        });
        return;
    }

    for (let index = 0; index < 3; index += 1) {
        const glint = document.createElement("span");
        const names = ["one", "two", "three"];
        glint.className = "farmago-logo-glint farmago-logo-glint-" + names[index];
        glint.setAttribute("aria-hidden", "true");
        logo.appendChild(glint);
    }

    const glints = gsap.utils.toArray(".farmago-logo-glint");

    const tl = gsap.timeline({
        defaults: { ease: "power3.out" },
        onComplete: function () {
            logo.classList.add("is-ready");
        }
    });

    tl.from(".farmago-login-brand", {
        opacity: 0,
        y: 14,
        scale: .94,
        filter: "blur(8px)",
        duration: .68
    })
        .from(logoImage, {
            opacity: 0,
            rotate: -2,
            scale: .9,
            duration: .62
        }, "-=.42")
        .to(glints, {
            opacity: 1,
            scale: 1.55,
            duration: .28,
            stagger: {
                each: .08,
                from: "center",
                grid: "auto"
            },
            yoyo: true,
            repeat: 1
        }, "-=.18");

    gsap.to(logoImage, {
        y: -4,
        duration: 2.4,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
        delay: .8
    });

    logo.addEventListener("mouseenter", function () {
        gsap.to(".farmago-login-brand", {
            y: -2,
            scale: 1.02,
            duration: .25,
            ease: "power2.out"
        });

        gsap.to(logoImage, {
            rotate: 1.4,
            scale: 1.01,
            duration: .25,
            ease: "power2.out"
        });
    });

    logo.addEventListener("mouseleave", function () {
        gsap.to(".farmago-login-brand", {
            y: 0,
            scale: 1,
            duration: .25,
            ease: "power2.out"
        });

        gsap.to(logoImage, {
            rotate: 0,
            scale: 1,
            duration: .25,
            ease: "power2.out"
        });
    });
});
