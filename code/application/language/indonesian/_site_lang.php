<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['status_level'] = array(
                KARYAWAN            => array(
                                                'text' => 'Karyawan',
                                            ),
                ATASAN_LANGSUNG     => array(
                                                'text' => 'Atasan Langsung'
                                            ),
                ATASAN_TDK_LANGSUNG => array(
                                                'text' => 'Atasan Tidak Langsung'
                                    ),
                KADIR_SDMO          => array(
                                                'text' => 'Kadir SDMO'
                                    ),
                KABAG_ADMIN         => array(
                                                'text' => 'Kabag Admin'
                                    )
);

$lang['status_approve'] = array(
                STATUS_ACCEPT       => array(
                                                'text' => 'Diterima',
                                                'label' => '<span class="badge badge-success">Diterima</span>'
                                            ),
                STATUS_REJECT       => array(
                                                'text' => 'Ditolak',
                                                'label' => '<span class="badge badge-danger">Ditolak</span>'
                                            ),
                STATUS_WAITING       => array(
                                                'text' => 'Menunggu',
                                                'label' => '<span class="badge badge-warning">Menunggu</span>'
                ),
                STATUS_NOT_USED      => array(
                                                'text' => 'Tidak perlu',
                                                'label' => '<span class="badge badge-info">Tidak perlu</span>'
                )
                );

$lang['jenis_cuti'] = array(
                    CUTI_TAHUNAN            => array(
                                                    'text' => 'Cuti Tahunan',
                                                ),
                    CUTI_BESAR              => array(
                                                    'text' => 'Cuti Besar'
                                                ),
                    CUTI_SAKIT              => array(
                                                    'text' => 'Cuti Sakit'
                                                ),
                    CUTI_LAHIR              => array(
                                                    'text' => 'Cuti Lahir/Gugur'
                                                ),
                    CUTI_HAJI               => array(
                                                    'text' => 'Cuti Haji/Umroh'
                                                ),
                    CUTI_DISPEN             => array(
                                                    'text' => 'Cuti Dispensasi dinas/non dinas'
                    )
                );


$lang['error_not_found'] = 'Data yang anda cari tidak ada';

$lang['data_agama'] = array(
                                1 => 'Islam',
                                2 => 'Kristen',
                                3 => 'Katolik',
                                4 => 'Budha',
                                5 => 'Hindu',
                                6 => 'Khonghucu',
                                7 => 'Lainnya'
);
