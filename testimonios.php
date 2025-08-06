<?php
require 'includes/config.php';

// Obtener testimonios aprobados y destacados
$testimonios = $db->query("
    SELECT t.*, s.email 
    FROM testimonios t
    JOIN suscriptores s ON t.usuario_id = s.id
    WHERE t.aprobado = 1
    ORDER BY t.destacado DESC, t.votos_positivos DESC
")->fetchAll();

include 'includes/header.php';
?>

<main class="container">
    <section class="testimonios-section">
        <h2>Historias que Inspiran</h2>
        <p class="subtitle">Descubre cómo otras mujeres han transformado sus vidas</p>
        
        <?php if (empty($testimonios)): ?>
            <div class="no-testimonios">
                <p>Aún no hay testimonios publicados. Sé la primera en compartir tu historia.</p>
        <?php else: ?>
            <div class="testimonios-grid">
                <?php foreach ($testimonios as $testimonio): ?>
                <div class="testimonio-card <?php echo $testimonio['destacado'] ? 'destacado' : ''; ?>">
                    <div class="testimonio-content">
                        <p><?php echo nl2br(htmlspecialchars($testimonio['contenido'])); ?></p>
                    </div>
                    <div class="testimonio-footer">
                        <div class="testimonio-author">
                            <span><?php echo substr(htmlspecialchars($testimonio['email']), 0, strpos($testimonio['email'], '@')); ?></span>
                        </div>
                        <div class="testimonio-votes">
                            <span class="upvote" data-id="<?php echo $testimonio['id']; ?>">
                                <i class="fas fa-thumbs-up"></i> <span><?php echo $testimonio['votos_positivos']; ?></span>
                            </span>
                            <span class="downvote" data-id="<?php echo $testimonio['id']; ?>">
                                <i class="fas fa-thumbs-down"></i> <span><?php echo $testimonio['votos_negativos']; ?></span>
                            </span>
                        </div>
                    </div>
                    <?php if ($testimonio['destacado']): ?>
                        <div class="destacado-badge">
                            <i class="fas fa-star"></i> Destacado
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="add-testimonio">
                <button id="showTestimonioForm" class="btn">Compartir Mi Historia</button>
                
                <form id="testimonioForm" method="POST" action="procesar_testimonio.php" style="display: none;">
                    <div class="form-group">
                        <label for="contenido">Mi testimonio</label>
                        <textarea id="contenido" name="contenido" rows="6" required 
                                  placeholder="Comparte cómo este programa ha impactado tu vida..."></textarea>
                    </div>
                    <button type="submit" class="btn">Enviar Testimonio</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>¿Quieres compartir tu experiencia? <a href="login.php">Inicia sesión</a> para publicar tu testimonio.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
// Mostrar/ocultar formulario
document.getElementById('showTestimonioForm').addEventListener('click', function() {
    const form = document.getElementById('testimonioForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});

// Sistema de votación
document.querySelectorAll('.upvote, .downvote').forEach(button => {
    button.addEventListener('click', function() {
        const testimonioId = this.getAttribute('data-id');
        const isUpvote = this.classList.contains('upvote');
        
        fetch('votar_testimonio.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${testimonioId}&tipo=${isUpvote ? 'up' : 'down'}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const countElement = this.querySelector('span:last-child');
                countElement.textContent = data.newCount;
            } else if (data.error) {
                alert(data.error);
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>