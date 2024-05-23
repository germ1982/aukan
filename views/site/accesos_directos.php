<?php
$accesos = [
    0 => [
        'titulo' => 'Sistemas Internos',
        'accesos' => [
            0 => [
                'nombre' => 'Hay Producto',
                'icono' => 'img/accesos_directos_home/hp.png',
                'link' => 'https://hayproducto.neuquen.gov.ar',
                'descripcion' => 'Catálogo de Emprendedores'
            ],
            1 => [
                'nombre' => 'Cumbre',
                'icono' => 'img/accesos_directos_home/cumbre.png',
                'link' => 'https://cumbre.neuquen.gov.ar',
                'descripcion' => 'Portal de Capacitaciones'
            ],
            2 => [
                'nombre' => 'MDSyT Web',
                'icono' => 'img/accesos_directos_home/web.png',
                'link' => 'https://mindesarrolloytrabajo.neuquen.gob.ar/',
                'descripcion' => 'Página Web del Ministerio'
            ],
            // 3 => [
            //     'nombre' => 'Rumbo',
            //     'icono' => 'img/accesos_directos_home/rumbo.png',
            //     'link' => 'https://rumbo.neuquen.gov.ar',
            //     'descripcion' => 'Portal de Empleo'
            // ],
            4 => [
                'nombre' => 'KUMEN',
                'icono' => 'img/accesos_directos_home/kumen.png',
                'link' => 'https://mds2.neuquen.gov.ar/moodle/login/index.php',
                'descripcion' => 'Plataforma Educativa'
            ],
            5 => [
                'nombre' => 'RUNNEU',
                'icono' => 'img/accesos_directos_home/runneu.png',
                'link' => 'https://runneu.neuquen.gov.ar',
                'descripcion' => 'Registro Único Nominal'
            ]
        ]
    ],
    1 => [
        'titulo' => 'Sistemas Troncales',
        'accesos' => [
            1 => [
                'nombre' => 'GDE',
                'icono' => 'img/accesos_directos_home/gde.png',
                'link' => 'https://gde.neuquen.gov.ar/',
                'descripcion' => 'Gestión de Documentación Electrónica'
            ],
            2 => [
                'nombre' => 'SAFIPRO',
                'icono' => 'img/accesos_directos_home/safipro.png',
                'link' => 'http://prodz1.neuquen.gov.ar:7778/SafiproProduccion/faces/app/login/login.jspx',
                'descripcion' => 'Sistema de Administración Financiera'
            ],
            3 => [
                'nombre' => 'Web Mail',
                'icono' => 'img/accesos_directos_home/mail.png',
                'link' => 'https://correo.neuquen.gov.ar/',
                'descripcion' => 'Sistema de Mensajería Electrónica'
            ],
            4 => [
                'nombre' => 'One Login',
                'icono' => 'img/accesos_directos_home/onelogin.png',
                'link' => 'https://cas.neuquen.gov.ar/cas/login',
                'descripcion' => 'Directorio de Aplicaciones'
            ],
            5 => [
                'nombre' => 'Mis Licencias',
                'icono' => 'img/accesos_directos_home/mis_licencias.png',
                'link' => 'https://mislicencias.economianqn.gob.ar/#/auth/login',
                'descripcion' => 'Acceso a Mis Licencias'
            ]
        ]
    ]
];
?>
<style>
    img.accesos {
        max-width: 80px;
        padding: 0 1rem;
        margin-top: 5px;
    }

    h4.accesos {
        margin: revert;
    }
</style>
<?php foreach ($accesos as $fila) : ?>
    <div class="col-md-4" style="padding-left: 10px;">
        <section class="panel panel-featured-left panel-featured-primary">
            <header class="panel-heading ">
                <div class="panel-actions">
                    <!-- onclick="javascript:editarPerfil();" -->                    
                </div>
                <h2 class="panel-title text-center"><?= $fila['titulo'] ?></h2>
            </header>
            <div class="panel-body">
                <div class="row justify-content-between" >
                    <?php foreach ($fila['accesos'] as $elto) : ?>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" data-toggle="tooltip" data-placement="bottom" title="<?php echo $elto['descripcion'] ?>" data-original-title="<?php echo $elto['descripcion'] ?>">
                            <a style="text-decoration: none" target="_blank" href="<?php echo $elto['link'] ?>">
                                <div class="widget-summary widget-summary-xs">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <img class="accesos" src="<?php echo $elto['icono'] ? $elto['icono'] : 'img/accesos_directos_home/default.png' ?>" alt="">
                                    </div>
                                    <!-- <div class="widget-summary-col align-middle">
                                        <h4 class="accesos" style="color: #333333">
                                            <span><?php /* $elto['nombre']  */ ?></span>
                                        </h4>
                                    </div> -->
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
<?php endforeach; ?>