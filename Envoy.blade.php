@servers(['web' => '104.131.178.199'])

@task('import')
    cd /home/forge/webefficiency.com.br
    php artisan webef:import
@endtask
