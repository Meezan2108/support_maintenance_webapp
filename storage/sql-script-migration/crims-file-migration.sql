
                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther1 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther1.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther1 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther1.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther1 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther1.xlsx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther2 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther2.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther2 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther2.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther2 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther2.xlsx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther3 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther3.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther3 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther3.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther3 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther3.xlsx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther4 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther4.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther4 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther4.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther4 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther4.xlsx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther5 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther5.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther5 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther5.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileOther5 AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileOther5.xlsx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileProposal AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileProposal.docx';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileProposal AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileProposal.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.FileProposal AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.proposal target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Proposal'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Proposal'
                    JOIN rndmcbv2.dbo.Proposal source_tbl
                        ON o.original_id = source_tbl.IDProposal
                        AND o.originalable_type = 'App\Models\Proposal'
                    WHERE f.file_name = 'FileProposal.xlsx';


                    
                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.filereport AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.recognition target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\Recognition'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\Recognition'
                    JOIN rndmcbv2.dbo.Recognition source_tbl
                        ON o.original_id = source_tbl.IDRecognition
                        AND o.originalable_type = 'App\Models\Recognition'
                    WHERE f.file_name = 'FileRecognition.pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.ReportFile AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.report_end_project target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\ReportEndProject'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\ReportEndProject'
                    JOIN rndmcbv2.dbo.Report source_tbl
                        ON o.original_id = source_tbl.idreport
                        AND o.originalable_type = 'App\Models\ReportEndProject'
                    WHERE f.file_name = 'ReportFile..pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.ReportFile AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.report_end_project target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\ReportEndProject'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\ReportEndProject'
                    JOIN rndmcbv2.dbo.Report source_tbl
                        ON o.original_id = source_tbl.idreport
                        AND o.originalable_type = 'App\Models\ReportEndProject'
                    WHERE f.file_name = 'ReportFile..docx';

                    
                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.ReportFile AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.report_milestone target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\ReportMilestone'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\ReportMilestone'
                    JOIN rndmcbv2.dbo.Report source_tbl
                        ON o.original_id = source_tbl.idreport
                        AND o.originalable_type = 'App\Models\ReportMilestone'
                    WHERE f.file_name = 'ReportFile..pdf';


                    UPDATE CRIMS.dbo.fileable
                    SET CRIMS.dbo.fileable.[file] = CAST(source_tbl.ReportFile AS VARBINARY(MAX)),
                        CRIMS.dbo.fileable.[updated_at] = SYSDATETIME()
                    FROM CRIMS.dbo.fileable f
                    JOIN CRIMS.dbo.report_milestone target_tbl
                        ON f.fileable_id = target_tbl.id
                        AND f.fileable_type = 'App\Models\ReportMilestone'
                    JOIN CRIMS.dbo.originalable o
                        ON o.originalable_id = target_tbl.id
                        AND o.originalable_type = 'App\Models\ReportMilestone'
                    JOIN rndmcbv2.dbo.Report source_tbl
                        ON o.original_id = source_tbl.idreport
                        AND o.originalable_type = 'App\Models\ReportMilestone'
                    WHERE f.file_name = 'ReportFile..docx';