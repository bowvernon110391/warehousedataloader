REPLACE INTO
	wkt_pib_rekam_siap_jalur
	(
		SEQ_PIB,
		CAR,
		TGL_PIB,
		NO_PIB,
		TGL_SPPB,
		NO_SPPB,
		WP_IMP,
		NM_IMP,
		ID_PPJK,
		NM_PPJK,
		GUDANG,
		JLR,
		J_BRG,
		J_CONT,
		
		WKT_HC,
		WK_TJK_PB,
		WKM_PB,
		WKS_PB,
		WKM_LHP,
		WKS_LHP,
		
		NIP_PFPB,
		NAMA_PFPB,
		
		WKM_PD,
		WKS_PD,
		
		NIP_PFPD,
		NAMA_PFPD,
		
		WK_INP,
		WK_DNP,
		WK_LOAD,
		WK_MULAI,
		WK_JALUR,
		WK_LAB,
		WK_KONF_BRG,
		WK_AUDIT,
		WK_TNPFPD,
		WK_PEMB,
		WK_KONSULTASI,
		WK_SPPB,
		WK_SPKPBM,
		WK_AMBIL_JALUR,
		WK_REKAM_SIAP_JALUR,
		WK_SIAP
	)
VALUES
(
	?,	-- 1
	?,
	STR_TO_DATE( ? , '%d-%m-%Y' ),
	?,
	STR_TO_DATE( ? , '%m/%d/%Y %H:%i:%s' ),	-- 5
	?,
	?,
	?,
	?,
	?,	-- 10
	?,
	?,
	NULLIF( ?, 'NULL'),
	NULLIF( ?, 'NULL'),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),			-- 15
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),		-- 20
	?,
	?,
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	?,	-- 25
	?,
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' ),
	STR_TO_DATE( ? , '%d-%m-%Y %H:%i:%s' )
)
