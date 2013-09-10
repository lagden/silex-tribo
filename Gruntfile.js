'use strict';

module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // Concat
        concat: {
            options: {
                separator: ';'
            },
            frameworks: {
                src: [
                // jQuery
                'bower_components/jquery/jquery.js'],
                dest: 'public/Scripts/dist/frameworks.js'
            },
            vendor: {
                src: [
                    // GreenSock
                    'bower_components/GreenSock-JS/src/uncompressed/TweenMax.js',
                    'bower_components/GreenSock-JS/src/uncompressed/jquery.gsap.js',
                    // Flexslider
                    'bower_components/flexslider/jquery.flexslider.js',
                    // Isotope
                    'bower_components/isotope/jquery.isotope.js',
                    // Infinite Scroll
                    'bower_components/infinite-scroll/jquery.infinitescroll.js',
                    // fancyBox
                    'bower_components/fancybox/source/jquery.fancybox.js',
                    // theCombo
                    'bower_components/jquery.theCombo.js/jquery.theCombo.js',
                    // swfobject
                    'bower_components/swfobject/swfobject/src/swfobject.js'
                ],
                dest: 'public/Scripts/dist/vendor.js'
            },
            app: {
                src: [
                    'public/Scripts/main.js',
                    'public/Scripts/grid.js'
                ],
                dest: 'public/Scripts/dist/base.js'
            },
            dev: {
                options: {
                    separator: ';'
                },
                src: [
                    'public/Scripts/dist/frameworks.js',
                    'public/Scripts/dist/vendor.js',
                    'public/Scripts/dist/base.js'],
                dest: 'public/Scripts/output.js'
            }
        },
        // Uglify
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> */',
                properties: true,
                compress: {
                    global_defs: {
                        "DEBUG": false
                    },
                    dead_code: true
                }
            },
            target: {
                files: {
                    'public/Scripts/output.js': ['public/Scripts/output.js']
                }
            },
            // Html5 Shiv
            ie: {
                files: {
                    'public/Scripts/html5shiv.js': ['bower_components/html5shiv/src/html5shiv.js']
                }
            }
        },
        // Compass
        compass: {
            dev: {
                options: {
                    config: 'config.dev.rb'
                }
            },
            prod: {
                options: {
                    config: 'config.rb'
                }
            }
        },
        // Watch
        watch: {
            js: {
                files: ['public/Scripts/main.js','public/Scripts/grid.js'],
                tasks: ['concat', 'uglify:ie', 'clean'],
                options: {
                    livereload: true
                }
            },
            css: {
                files: ['sass/**/*.scss'],
                tasks: ['compass:dev'],
                options: {
                    livereload: true
                }
            }
        },
        // Clean
        clean: {
            options: {
              force: true
            },
            dist: ['public/Scripts/dist']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.registerTask('default', ['concat', 'uglify', 'clean', 'compass:prod']);
};