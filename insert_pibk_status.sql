REPLACE INTO
    pibk_status
    (
        ID_AJU,
        ID_STATUS,
        NOMOR_BARANG,
        KD_STATUS,
        NIP,
        WK_REKAM,
        FL_KIRIM_RESPON,
        WK_KIRIM_RESPON,
        FL_BC11_NULL,
        NPWP_PEMBERITAHU,
        TGL_BLAWB,
        URAIAN
	)
VALUES
(
    NULLIF(?, 'NULL'),
    NULLIF(?, 'NULL'),
    ?,
    NULLIF(?, 'NULL'),
    ?,
    STR_TO_DATE(?,'%m/%d/%Y %H:%i:%s'),
    ?,
    STR_TO_DATE(?,'%m/%d/%Y %H:%i:%s'),
    ?,
    ?,
    STR_TO_DATE(?,'%m/%d/%Y %H:%i:%s'),
    ?
)