<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Section-->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="font-weight-bold mb-5 text-dark">
                    <img alt="<?php echo $this->options->get('site_name');?>"
                        src="<?php echo  apply_filters( 'fill_apps_logo', '' ); ?>" class="max-h-40px" />
                </h3>
                <div class="font-weight-nromal font-size-lg mb-5">
                    <p class="lead">
                        <?php echo $this->options->get( 'site_name' ) .' '. __("is up to date"); ?> <br>
                        <?php echo sprintf( __( 'Version <b>%s</b> (Official build)' ), $this->options->get('latest_release')['version'] );?>
                    </p>

                    <?php if (riake('site_title', $this->options->get())) : ?>
                    <p class="flex-column justify-content-end d-flex">
                        <span
                            class="opacity-50 font-weight-bold font-size-sm"><?php echo $this->options->get('site_name');?>
                            for</span>
                        <span class="font-size-md"><?php echo riake('site_title', $this->options->get());?></span>
                    </p>
                    <?php endif; ?>

                    <!-- Cek Update -->
                    <?php if ($update === 'unknow-release') : ?>
                    <?php elseif ($update === 'not-available') : ?>
                    <?php elseif ($update === 'old-release') : ?>
                    <?php else : ?>
                    <h6 class="font-weight-bold mb-0 text-dark">
                        <?php echo sprintf(__('%s : %s is available'), $this->options->get('site_name'), riake('name', $update)); ?>
                    </h6>

                    <p><?php echo $this->markdown->parse(riake('description', $update)); ?></p>

                    <a class="btn btn-primary btn_update" href="<?php echo riake('tag_name', $update); ?>">
                        <i class="fas fa-circle-notch"></i> <?php _e('Click Here to Update'); ?>
                    </a>
                    <p id="update"></p>
                    <?php endif; ?>

                    <p class="flex-column justify-content-end d-flex mt-5">
                        <span class="opacity-50 font-weight-bold font-size-sm">
                            <?php echo sprintf(__('If you need help with the %s, please Contact Us '), $this->options->get('site_name'));?>
                        </span>
                        Telp. - HP.
                        <?php echo riake('admin_contact', $this->options->get(), '(+62) 822 2069 4668'); ?>
                        <br>
                        <?php echo riake('mail_address', $this->options->get('mail')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end::Section-->

<script>
$(document).on('click', '.btn_update', function(e) {
    e.preventDefault();

    var id = $(this).attr('href');

    $('.btn_update .fa-circle-notch').addClass('fa-spin')
    download(id);
});

function download(id) {
    var route = 'about/download';
    var formData = {
        id: id
    };

    // Ajax config
    ajaxSetup(route, formData);
    $.ajax({
        beforeSend: function() {
            $('#update').append(
                '<div id="download"><i class="fa fa-spinner fa-spin"></i><?php _e('Downloading Zip file...'); ?></div>'
            );
        },
        success: function(result, status, xhr) {
            $('#download .fa-spin').remove();
            $('#download').addClass('font-weight-bold');
            extract();
        },
        error: function(xhr, status, error) {
            $('#update').append(
                '<div><?php _e('An error occured during download...'); ?></div>'
            );
        }
    });
}

function extract() {
    var route = 'about/extract';

    // Ajax config
    ajaxSetup(route);
    $.ajax({
        beforeSend: function() {
            $('#update').append(
                '<div id="extract"><i class="fa fa-spinner fa-spin"></i><?php _e('Extracting the new release...'); ?></div>'
            );
        },
        success: function(result, status, xhr) {
            $('#extract .fa-spin').remove();
            $('#extract').addClass('font-weight-bold');
            updated()
        },
        error: function(xhr, status, error) {
            $('#update').append(
                '<div><?php _e('An error occured during extraction...'); ?></div>'
            );
        }
    });
}

function install() {
    var route = 'about/install';

    // Ajax config
    ajaxSetup(route);
    $.ajax({
        beforeSend: function() {
            $('#update').append(
                '<div id="install"><i class="fa fa-spinner fa-spin"></i><?php _e('Installing the new release...'); ?></div>'
            );
        },
        success: function(result, status, xhr) {
            $('#install .fa-spin').remove();
            $('#install').addClass('font-weight-bold');
            updated()
        },
        error: function(xhr, status, error) {
            $('#update').append(
                '<div><?php _e('An error occured during install...'); ?></div>'
            );
        }
    });
}

function updated() {
    var route = 'about/updated';

    // Ajax config
    ajaxSetup(route);
    $.ajax({
        beforeSend: function() {
            $('#update').append(
                '<div id="updated"><i class="fa fa-spinner fa-spin"></i><?php _e('Release...'); ?></div>'
            );
        },
        success: function(result, status, xhr) {
            window.location.replace(result.url);
        },
        error: function(xhr, status, error) {
            $('#update').append(
                '<div><?php _e('An error occured during updated...'); ?></div>'
            );
        }
    });
}
</script>