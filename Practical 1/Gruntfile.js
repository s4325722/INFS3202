/**
 * Created by Blake on 12/03/2014.
 */

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                src: 'src/<%= pkg.name %>.js',
                dest: 'build/<%= pkg.name %>.min.js'
            }
        },
//        include_bootstrap: {
//            files: {
//                'build/assets/css/styles.css': 'src/less/manifest.less'
//            }
//        },

        include_bootstrap: {
            options: {
                // All options are passed on to the grunt-contrib-less task
            },
            production: {
                files: {
                    'build/assets/css/styles.css': 'src/less/manifest.less'
                }
            }
        },

        less: {
//            development: {
//                options: {
//                    paths: ["src/less"]
//                },
//                files: {
//                }
//            },
            production: {
                options: {
                    paths: ["src/less"],
                    cleancss: true
                },
                files: {
                    //'build/assets/css/styles.css': 'src/less/manifest.less'
                }
            }
        },
        dart2js: {
            options: {
                dart2js_bin: '/usr/local/dart-sdk/bin/dart2js',
                force: true

                // Task-specific options go here.
            },
            production: {
                files: {
                    'build/assets/js/main.js': 'src/dart/main.dart'
                }
            }
        },
        copy: {
            production: {
                files: [
                    // includes files within path
                    {expand: true, cwd: 'src/', src: ['*.html'], dest: 'build/', filter: 'isFile'},
                    {expand: true, cwd: 'src/dart/packages/browser', src: ['dart.js'], dest: 'build/assets/js', filter: 'isFile'}
                ]
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    //grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-include-bootstrap');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-dart2js');
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task(s).
    grunt.registerTask('default', [/*'uglify'*/ 'include_bootstrap', 'less', 'dart2js', 'copy']);

};