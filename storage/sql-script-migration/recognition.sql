SELECT o.* FROM fileable f
JOIN recognition r 
 	ON r.id = f.fileable_id 
	AND f.fileable_type = 'App\Models\Recognition'
JOIN originalable o
	ON r.id = o.originalable_id 
	AND o.originalable_type = 'App\Models\Recognition'
SET NewColumnName = CONVERT(VARBINARY(MAX), OldColumnName);
	
	
