SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
 CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
                    IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),''))  as vendedor,porcentajeComision,aCom.precioComision,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100)  FROM catFormaPagoComisiones cfpc where noPago =1 and cfpc.idPagaComision = cpc.idPagaComision )as pago_1,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100) FROM catFormaPagoComisiones cfpc where noPago =2 and cfpc.idPagaComision = cpc.idPagaComision )as pago_2,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100) FROM catFormaPagoComisiones cfpc where noPago =3 and cfpc.idPagaComision = cpc.idPagaComision )as pago_3,
(SELECT SUM(monto) from pagosComision pc where pc.idEnganche=e.idEnganche and pc.idFormaPagoComisiones = catpc.idFormaPagoComisiones) as totalPagado 
FROM enganche e 
INNER JOIN usuarios u on e.idVendedor = u.id_usuario
INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
INNER JOIN torres t on e.torres = t.noTorre
INNER JOIN niveles n on e.nivel = n.noNivel
INNER JOIN apartamentos a on e.apartamento = a.apartamento
INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = "Vendedores"
INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche                                                                                      
GROUP BY e.idEnganche
UNION
SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
 'Pedro Arguello'  as vendedor,porcentajeComision,aCom.precioComision,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100) FROM catFormaPagoComisiones cfpc where noPago =1 and cfpc.idPagaComision = cpc.idPagaComision )as pago_1,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100) FROM catFormaPagoComisiones cfpc where noPago =2 and cfpc.idPagaComision = cpc.idPagaComision )as pago_2,
( SELECT aCom.precioComision * (porcentajeComision/100) * (cfpc.porcentajePago/100) FROM catFormaPagoComisiones cfpc where noPago =3 and cfpc.idPagaComision = cpc.idPagaComision )as pago_3,
( SELECT SUM(monto) from pagosComision pc where pc.idEnganche=e.idEnganche and pc.idFormaPagoComisiones = catpc.idFormaPagoComisiones) as totalPagado 
FROM enganche e 
INNER JOIN usuarios u on e.idVendedor = u.id_usuario
INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
INNER JOIN torres t on e.torres = t.noTorre
INNER JOIN niveles n on e.nivel = n.noNivel
INNER JOIN apartamentos a on e.apartamento = a.apartamento
INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = "Director de Ventas"
INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche
GROUP BY e.idEnganche
ORDER BY proyecto,noTorre,noNivel,apartamento