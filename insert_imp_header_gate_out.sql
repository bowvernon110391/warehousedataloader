REPLACE INTO
    imp_header_gate_out
    (
        KODE_KANTOR,
        NO_PIB,
        TGL_PIB,
        NO_BC11,
        TGL_BC11,
        IMPORTIR,
        NPWP_IMPORTIR,
        NO_SPPB,
        TGL_SPPB,
        STATUS_JALUR,
        WK_GATE_OUT,
        GUDANG,
        NO_MAWB,
        TGL_MAWB,
        NO_HAWB,
        TGL_HAWB,
        MASKAPAI,
        NO_VOYAGE
    )
VALUES
(
    ?,
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    ?,
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),
    ?,
    ?
)