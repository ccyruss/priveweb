/* **************************************************************************** 
                          Custom GSAP js start 
****************************************************************************  */

var tl = gsap.timeline(); 
gsap.registerPlugin(ScrollTrigger, SplitText);
gsap.registerPlugin(ScrollTrigger, ScrollSmoother);



// =================================== Smooth Scroller Js Start =====================================
const smoother = ScrollSmoother.create({
  content: "#scrollSmoother-container",
  smooth: 1,
  effects: true,
  smoothTouch: 0.1,
  ease: 'power4.out',
});
// =================================== Smooth Scroller End Start =====================================




 // Section title Js
    if ($('.char-animation').length > 0) {
      let char_come = gsap.utils.toArray(".char-animation");
      char_come.forEach(splitTextLine => {
        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: splitTextLine,
            start: 'top 90%',
            end: 'bottom 60%',
            scrub: false,
            markers: false,
            toggleActions: 'play none none none'

          }
        });
        const itemSplitted = new SplitText(splitTextLine, { type: "chars, words" });
        gsap.set(splitTextLine, { perspective: 300 });
        itemSplitted.split({ type: "chars, words" })
        tl.from(itemSplitted.chars,
          {
            duration: 1,
            delay: 0.5,
            x: 100,
            autoAlpha: 0,
            stagger: 0.05
          });
      });
    }


    // custom click panel 
    function mediaSize() { 
        if (window.matchMedia('(min-width: 992px)').matches) {
          const panels = document.querySelectorAll('.col-custom')
          panels.forEach(panel => {
            panel.addEventListener('click', () => {
              removeActiveClasses()
              panel.classList.add('active')
            })
          })
          function removeActiveClasses() {
            panels.forEach(panel => {
              panel.classList.remove('active')
            })
          }
        } else {
          $(".col-custom ").addClass("active");
        }
      };
      mediaSize();
    window.addEventListener('resize', mediaSize, false);







    // advance card animation 
    gsap.registerPlugin(ScrollTrigger);

    document.addEventListener("DOMContentLoaded", function () {
      if (window.innerWidth > 768) {
        const items = document.querySelectorAll(".advance-wrap .advance-item");
        if (items.length < 5) return; // skip if items are missing

        const advanced = gsap.timeline({
          scrollTrigger: {
            trigger: ".advance-wrap",
            start: "top 60%",
            toggleActions: "play none none reverse",
            markers: false,
          },
          defaults: {
            ease: "ease1",
            duration: 1,
          },
        });

        advanced
          .from(items[0], {
            xPercent: 100,
            rotate: -8
          })
          .from(items[1], {
            xPercent: 30,
            rotate: 4.13
          }, "<")
          .from(items[2], {
            xPercent: -30,
            rotate: -6.42
          }, "<")
          .from(items[3], {
            xPercent: -60,
            rotate: -12.15
          }, "<")
          .from(items[4], {
            xPercent: -100,
            rotate: 12
          }, "<");
      }
    });





    // advance leftToRightTL scroll
    if (document.querySelector(".advance-bg-shape-1")) { 
      let leftToRightTL = gsap.timeline({
        scrollTrigger: {
          trigger: ".advance-bg-shape-1",
          start: "top 80%",
          end: "bottom 10%",
          scrub: 2,  
          markers: false,
        }
      });
      leftToRightTL.fromTo(".advance-bg-shape-1", 
        {
          x: -260,
        },  
        { 
          x: 0,
          duration: 1.6
        } 
      );
    }



    // Project left to right scroll
    let pp = gsap.matchMedia();
    pp.add("(min-width: 380px)", () => {
      const panelsSections = gsap.utils.toArray(".panels");
      for (let i = 0; i < panelsSections.length; i++) {
        const thePanelsSection = panelsSections[i];
        const panels = gsap.utils.toArray(".panels-container .panel", thePanelsSection);
        const panelsContainer = thePanelsSection.querySelector(".panels-container");
        const panelHeight = 320;
        gsap.set(panelsContainer, { height: panelHeight });
        gsap.set(panels, { height: panelHeight });
        let totalPanelsWidth = 0;
        panels.forEach((panel) => {
          totalPanelsWidth += $(panel).outerWidth(true); 
        });
        gsap.set(panelsContainer, { width: totalPanelsWidth });
        gsap.to(panels, {
          x: -totalPanelsWidth + innerWidth,
          ease: "none",
          scrollTrigger: {
            trigger: panelsContainer,
            pin: true,
            start: 'center center',
            end: "bottom 80%",
            scrub: 1,
            end: (st) => "+=" + (st.vars.trigger.offsetWidth - innerWidth),
          }
        });
      }
    });



	// testimonial scroll
	let pr = gsap.matchMedia();
	pr.add("(min-width: 991px)", () => {
		let tl = gsap.timeline();
		let projectpanels = document.querySelectorAll('.testimonial-panel')
		projectpanels.forEach((section, index) => {
			tl.to(section, {
				scrollTrigger: {
					trigger: section,
					pin: section,
					scrub: 1,
					start: 'center center',
					end: "bottom 90%",
					endTrigger: '.testimonial-panel-area',
					pinSpacing: false,
					markers: false,
				},
			})
		})
	});


  // Faq Sticky
  gsap.utils.toArray('.faq-sticky').forEach(sticky => {
      if (window.innerWidth < 0 || window.innerWidth > 991) {
        ScrollTrigger.create({
          trigger: sticky,
          start: 'top top+=180',
          end: '+=425',
          pin: true,
          scrub: true,
        });
      }
  });


    // gallery shape 1 leftToRightTL scroll
    if (document.querySelector(".gallery-shape-1")) { 
      let leftToRightTL = gsap.timeline({
        scrollTrigger: {
          trigger: ".gallery-shape-1",
          start: "top 80%",
          end: "bottom 10%",
          scrub: 2,  
          markers: false,
        }
      });
      leftToRightTL.fromTo(".gallery-shape-1", 
        {
          x: -260,
        },  
        { 
          x: 0,
          duration: 1.6
        } 
      );
    }

  // gallery shape 1 Righttoleft scroll
    if (document.querySelector(".gallery-shape-2")) { 
      let rightToLeftTL = gsap.timeline({
        scrollTrigger: {
          trigger: ".gallery-shape-2",
          start: "top 80%",
          end: "bottom 10%",
          scrub: 2,  
          markers: false,
        }
      });
      rightToLeftTL.fromTo(".gallery-shape-2", 
        {
          x: 260,
        },  
        { 
          x: 0,
          duration: 1.6
        } 
      );
    }




    // button hover animation Js
    $('.tw-hover-btn').on('mouseenter', function (e) {
        var x = e.pageX - $(this).offset().left;
        var y = e.pageY - $(this).offset().top;

        $(this).find('.tw-btn-circle-dot').css({
            top: y,
            left: x
        });
    });

    $('.tw-hover-btn').on('mouseout', function (e) {
        var x = e.pageX - $(this).offset().left;
        var y = e.pageY - $(this).offset().top;

        $(this).find('.tw-btn-circle-dot').css({
            top: y,
            left: x
        });
    });
    var hoverBtns = gsap.utils.toArray(".tw-hover-btn-wrapper");
    const hoverBtnItem = gsap.utils.toArray(".tw-hover-btn-item");
    hoverBtns.forEach((btn, i) => {
        $(btn).mousemove(function (e) {
            callParallax(e);
        });

        function callParallax(e) {
            parallaxIt(e, hoverBtnItem[i], 60);
        }

        function parallaxIt(e, target, movement) {
            var $this = $(btn);
            var relX = e.pageX - $this.offset().left;
            var relY = e.pageY - $this.offset().top;

            gsap.to(target, 1, {
                x: ((relX - $this.width() / 2) / $this.width()) * movement,
                y: ((relY - $this.height() / 2) / $this.height()) * movement,
                ease: Power2.easeOut,
            });
        }
        $(btn).mouseleave(function (e) {
            gsap.to(hoverBtnItem[i], 1, {
                x: 0,
                y: 0,
                ease: Power2.easeOut,
            });
        });
    });



    // Home 2 advance card animation 
document.addEventListener("DOMContentLoaded", function () {
    if (window.innerWidth > 768) {
        const items = document.querySelectorAll(".advance-two-item");
        if (!items || items.length < 5) return; // skip if fewer than 5 items

        const advanced = gsap.timeline({
            scrollTrigger: {
                trigger: ".advance-two-wrap",
                start: "top 60%",
                toggleActions: "play none none reverse",
                markers: false,
            },
            defaults: {
                ease: "ease1",
                duration: 1,
            },
        });

        advanced
            .from(items[0], {
                xPercent: 100,
                yPercent: 3,
                rotate: -5.39
            })
            .from(items[1], {
                xPercent: 50,
                yPercent: -5,
                rotate: -2.28
            }, "<")
            .from(items[2], {
                xPercent: 0,
                yPercent: -10,
                rotate: 0
            }, "<")
            .from(items[3], {
                xPercent: -50,
                yPercent: -5,
                rotate: 2.41
            }, "<")
            .from(items[4], {
                xPercent: -100,
                yPercent: 3,
                rotate: 5.27
            }, "<");
    }
});




    // 13. Mouse Custom Cursor 
    function itCursor() {
      var myCursor = jQuery(".mouseCursor");
      if (myCursor.length) {
        if ($("body")) {
          const e = document.querySelector(".cursor-inner"),
            t = document.querySelector(".cursor-outer");
          let n,
            i = 0,
            o = !1;
          (window.onmousemove = function (s) {
            o ||
              (t.style.transform =
                "translate(" + s.clientX + "px, " + s.clientY + "px)"),
              (e.style.transform =
                "translate(" + s.clientX + "px, " + s.clientY + "px)"),
              (n = s.clientY),
              (i = s.clientX);
          }),
            $("body").on("mouseenter", "button, a, .cursor-pointer", function () {
              e.classList.add("active"), t.classList.add("active");
            }),
            $("body").on("mouseleave", "button, a, .cursor-pointer", function () {
              ($(this).is("a", "button") &&
                $(this).closest(".cursor-pointer").length) ||
                (e.classList.remove("active"),
                  t.classList.remove("active"));
            }),
            (e.style.visibility = "visible"),
            (t.style.visibility = "visible");
        }
      }
    }
    itCursor();

    $(".tp-cursor-point-area").on("mouseenter", function () {
      $(".mouseCursor").addClass("cursor-big");
    });

    $(".tp-cursor-point-area").on("mouseleave", function () {
      $(".mouseCursor").removeClass("cursor-big");
    });

    $(".tp-cursor-point-area-2").on("mouseenter", function () {
      $(".cursor-inner").addClass("active");
    });

    $(".tp-cursor-point-area-2").on("mouseleave", function () {
      $(".cursor-inner").removeClass("active");
    });



    // 20. hover reveal for image Js
    const hoverItem = document.querySelectorAll(".wt-hover__reveal-item");
    function moveImage(e, hoverItem, index) {
        const item = hoverItem.getBoundingClientRect();
        const x = e.clientX - item.x;
        const y = e.clientY - item.y;
        if (hoverItem.children[index]) {
            hoverItem.children[index].style.transform = `translate(${x}px, ${y}px)`;
        }
    }
    hoverItem.forEach((item, i) => {
        item.addEventListener("mousemove", (e) => {
            setInterval(moveImage(e, item, 1), 50);
        });
    });



	// testimonial scroll
	let = gsap.matchMedia();
	pr.add("(min-width: 991px)", () => {
		let tl = gsap.timeline();
		let projectpanels = document.querySelectorAll('.blog-panel')
		projectpanels.forEach((section, index) => {
			tl.to(section, {
				scrollTrigger: {
					trigger: section,
					pin: section,
					scrub: 1,
					start: 'center center',
					end: "bottom 80%",
					endTrigger: '.blog-panel-area',
					pinSpacing: false,
					markers: false,
				},
			})
		})
	});


  // banner Rightt shape animation
  if (document.querySelector(".banner-three-shape-2")) { 
    let rightToLeftTL = gsap.timeline({
      scrollTrigger: {
        trigger: ".banner-three-shape-2",
        start: "top 80%",
        end: "bottom 10%",
        scrub: 2,  
        markers: false,
      }
    });
    rightToLeftTL.fromTo(".banner-three-shape-2", 
      {
        x: 260,
      },  
      { 
        x: 0,
        duration: 1.6
      } 
    );
  }

    // about three split invert Text Js
    const split = new SplitText(".text-invert", {
        type: "lines"
    });
    split.lines.forEach(target => {
        gsap.to(target, {
            backgroundPositionX: 0,
            ease: "none",
            scrollTrigger: {
                trigger: target,
                scrub: 1,
                start: "top 85%",
                end: "bottom center",
            },
        });
    });



    // Home three destination Js
    let dd = gsap.matchMedia();
    pp.add("(min-width: 1200px)", () => {
      const panelsSections = gsap.utils.toArray(".panels-three");
      for (let i = 0; i < panelsSections.length; i++) {
        const thePanelsSection = panelsSections[i];
        const panels = gsap.utils.toArray(".panels-three-container .panel-three", thePanelsSection);
        const panelsContainer = thePanelsSection.querySelector(".panels-three-container");
        const panelHeight = 643;
        gsap.set(panelsContainer, { height: panelHeight });
        gsap.set(panels, { height: panelHeight });
        let totalPanelsWidth = 0;
        panels.forEach((panel) => {
          totalPanelsWidth += $(panel).outerWidth(true); 
        });
        gsap.set(panelsContainer, { width: totalPanelsWidth });
        gsap.to(panels, {
          x: -totalPanelsWidth + innerWidth,
          ease: "none",
          scrollTrigger: {
            trigger: panelsContainer,
            pin: true,
            start: '30% center',
            end: "bottom 80%",
            scrub: 1,
            end: (st) => "+=" + (st.vars.trigger.offsetWidth - innerWidth),
          }
        });
      }
    });



    // gallery animation
	if ($('.gallery-three-area').length > 0) {
		if (window.matchMedia("(min-width: 1200px)").matches) {
		    let gallery = gsap.timeline({
			   scrollTrigger: {
				  trigger: ".gallery-three-area",
				  start: "top 30%",
				  pin: true,
				  markers: false,
				  scrub: 1,
				  pinSpacing: false,
				  end: "bottom 100%",
				  duration: 3,
			   }
		    });
		    gallery.to(".gallery-three-thumb img", {
			   width: "580px",
			   height: "580px",
		    });
		}
	 }



    // Faq Sticky
    gsap.utils.toArray('.faq-ip-sticky').forEach(sticky => {
        if (window.innerWidth < 0 || window.innerWidth > 991) {
          ScrollTrigger.create({
            trigger: sticky,
            start: 'top top+=180',
            end: '+=530',
            pin: true,
            scrub: true,
          });
        }
    });



    
    // plane animation item 1
    if ($('.line_shape').length > 0 ) {
      const path = document.getElementById('line_path');
      const plane = document.getElementById('paper-plane');
      const pathLength = path.getTotalLength();
      let progress = 0.5; 
      let speed = 0.0012; 
      function animatePlane() {
        const point = path.getPointAtLength(progress * pathLength);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y})`);
        const tangent = path.getPointAtLength((progress + 0.01) * pathLength);
        const angle = Math.atan2(tangent.y - point.y, tangent.x - point.x);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y}) rotate(${angle * 180 / Math.PI})`);
        progress += speed;
        if (progress > 1) {
          progress = 0;
        }
        requestAnimationFrame(animatePlane);
      }
      animatePlane();
    };
    

    // plane animation item 2
    if ($('.line_shape_2').length > 0 ) {
      const path = document.getElementById('line_path_2');
      const plane = document.getElementById('paper-plane_2');
      const pathLength = path.getTotalLength();
      let progress = 0.5; 
      let speed = 0.0012; 
      function animatePlane() {
        const point = path.getPointAtLength(progress * pathLength);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y})`);
        const tangent = path.getPointAtLength((progress + 0.01) * pathLength);
        const angle = Math.atan2(tangent.y - point.y, tangent.x - point.x);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y}) rotate(${angle * 180 / Math.PI})`);
        progress += speed;
        if (progress > 1) {
          progress = 0;
        }
        requestAnimationFrame(animatePlane);
      }
      animatePlane();
    };

    // plane animation item 3
    if ($('.line_shape_3').length > 0 ) {
      const path = document.getElementById('line_path_3');
      const plane = document.getElementById('paper-plane_3');
      const pathLength = path.getTotalLength();
      let progress = 0.5; 
      let speed = 0.0012; 
      function animatePlane() {
        const point = path.getPointAtLength(progress * pathLength);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y})`);
        const tangent = path.getPointAtLength((progress + 0.01) * pathLength);
        const angle = Math.atan2(tangent.y - point.y, tangent.x - point.x);
        plane.setAttribute('transform', `translate(${point.x}, ${point.y}) rotate(${angle * 180 / Math.PI})`);
        progress += speed;
        if (progress > 1) {
          progress = 0;
        }
        requestAnimationFrame(animatePlane);
      }
      animatePlane();
    };



    gsap.utils.toArray('.project-bg').forEach(container => {
      const img = container.querySelector('img');
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: container,
          scrub: true,
          pin: false,
        }
      });
      tl.fromTo(img, {
        yPercent: -20,
        ease: 'none'
      }, {
        yPercent: 20,
        ease: 'none'
      });
    });

















/* **************************************************************************** 
                          Custom GSAP js start 
****************************************************************************  */