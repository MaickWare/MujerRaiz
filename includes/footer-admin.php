<?php
/**
 * Footer del Panel de Administración
 */
?>
        </main> <!-- Cierre del main container abierto en header-admin.php -->

        <footer class="admin-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>Mujer, ¡Tú Puedes!</h4>
                        <p>Plataforma de empoderamiento femenino</p>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Enlaces Rápidos</h4>
                        <ul>
                            <li><a href="../index.php">Ir al Sitio Principal</a></li>
                            <li><a href="usuarios.php">Gestión de Usuarios</a></li>
                            <li><a href="contactos.php">Mensajes de Contacto</a></li>
                            <li><a href="testimonios.php">Testimonios</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Soporte</h4>
                        <ul>
                            <li><a href="mailto:softwaremaick@gmail.com">Contactar al Administrador</a></li>
                            <li><a href="#">Documentación</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Mujer, ¡Tú Puedes! - Todos los derechos reservados</p>
                    <p class="version">v1.0.0</p>
                </div>
            </div>
        </footer>

        <!-- Scripts comunes -->
        <script src="../assets/js/admin.js"></script>
        
        <!-- Scripts específicos por página -->
        <?php if (isset($scripts_adicionales)): ?>
            <?php foreach ($scripts_adicionales as $script): ?>
                <script src="<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>