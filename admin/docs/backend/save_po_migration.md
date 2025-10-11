# 🧾 Estabilización de `save_po()` — Orbyx Technologies  
**Fecha:** 10 de octubre de 2025  
**Autor:** Mónica Evelyn Mendoza Fernández  
**Proyecto:** Sistema de Inventarios / Módulo de Cotizaciones  

---

## 📘 Contexto

Durante las pruebas del módulo **Cotizaciones (purchase_order_list)**, el sistema presentó errores recurrentes al guardar registros:

- `EXCEPCIÓN: Field 'address' doesn't have a default value`
- `EXCEPCIÓN: Incorrect double value: '' for column 'discount_perc'`
- `#1406 - Data too long for column 'address'`
- Rollbacks automáticos y errores de conexión en MySQL 8+

Estos problemas surgieron tras cambios de entorno (modo estricto en MySQL, importaciones de estructura o ajustes de columnas sin valor por defecto).

---

## ⚙️ Causas detectadas

1. **MySQL en modo `STRICT_TRANS_TABLES`**
   - MySQL dejó de convertir `'' → 0` y `NULL → ''` automáticamente.
   - Cualquier cadena vacía en un campo `FLOAT`, `DOUBLE` o `INT` provoca error.

2. **Campos sin valor por defecto**
   - `address`, `cperson`, `discount_perc`, `tax_perc`, `amount`.

3. **Longitudes insuficientes**
   - Algunas direcciones excedían el límite de `VARCHAR`.

4. **Inserciones de cadenas vacías**
   - Formularios que enviaban `''` a columnas numéricas.

---

## 🧩 Solución aplicada en `save_po()`

Se implementó una nueva versión del método `save_po()` con validaciones seguras y conversión automática de datos.

### 🔑 Cambios clave

| Tipo de cambio | Descripción | Efecto |
|----------------|--------------|--------|
| Conversión de valores | Todos los campos numéricos se normalizan con `$toFloat()` | Evita `Incorrect double value` |
| Validación de proveedor | Se asigna proveedor genérico si falta `supplier_id` | Evita error de llave foránea |
| Creación automática de proveedor genérico | Se asegura existencia de `Proveedor Genérico` (ID=1) | Inserciones seguras |
| Conversión segura de cadenas | `NULL` o `''` → `'0.0'` o `''` según tipo | Compatible con MySQL 8 |
| Transacciones completas | Uso de `BEGIN`, `COMMIT`, `ROLLBACK` | Integridad garantizada |
| Normalización de totales | Descuentos, impuestos y montos calculados correctamente | Previene cálculos inconsistentes |

---

## 🧮 Conversor numérico seguro

```php
$toFloat = function($v){
    if ($v === null || $v === '' || is_bool($v)) return 0.0;
    $v = trim((string)$v);
    $v = str_replace([',',' '], ['',''], $v);
    return is_numeric($v) ? (float)$v : 0.0;
};
```

✅ Esta función garantiza que *ningún valor vacío* en un campo numérico cause error SQL.

---

## 🧱 Ajustes en Base de Datos

### **company_list**
```sql
ALTER TABLE company_list 
MODIFY `address` VARCHAR(500) NULL DEFAULT '',
MODIFY `cperson` VARCHAR(255) NULL DEFAULT '';
```

### **supplier_list**
```sql
ALTER TABLE supplier_list 
MODIFY `address` VARCHAR(500) NULL DEFAULT '',
MODIFY `cperson` VARCHAR(255) NULL DEFAULT '';
```

**Efectos:**
- Se permite texto largo en direcciones y contactos.
- Evita errores por “Field doesn't have a default value”.

---

## 🧠 Recomendaciones

- Mantener MySQL en modo estricto en producción.
- En desarrollo, se puede desactivar temporalmente si se necesita flexibilidad:
  ```ini
  sql_mode = "NO_ENGINE_SUBSTITUTION"
  ```
- Aplicar gradualmente las mismas validaciones (`$toFloat`, sanitización, transacciones) en:
  - `save_receiving()`
  - `save_sale()`
  - `save_return()`

---

## 🚦 Plan de acción

| Etapa | Descripción | Estado |
|--------|--------------|--------|
| Documentar cambios | Registrar este archivo en `/docs/backend/changes/save_po_migration.md` | ✅ |
| Validar en entorno local | Confirmar inserciones y actualizaciones sin errores | ✅ |
| Extender protección | Replicar validaciones en otros métodos `save_*` | 🔜 |
| Añadir comentarios en código | Documentar cada método para mantenibilidad | 🔜 |
| Backup BD | Exportar estructura estable `/db/backups/schema_2025-10-10.sql` | 🔜 |

---

## 🧰 Versión estable aplicada

**Archivo:** `/classes/Master.php`  
**Función:** `save_po()`  
**Versión:** 2025.10.10-stable  
**Compatibilidad:** PHP 8.1+ / MySQL 8.0+  
**Autor técnico:** Evelyn Fernández  
**Revisión QA:** Orbyx Technologies Backend Team

---

🩵 *Este cambio garantiza la estabilidad del módulo de cotizaciones y prepara la base para las futuras validaciones progresivas en los demás métodos `save_*`.*

---
