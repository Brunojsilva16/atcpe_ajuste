<div class="admin-wrapper container my-5">

    <!-- Header Admin -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0 mt-4"><i class="fa-solid fa-users-gear me-2 text-primary"></i> Painel de Gestão</h2>
            <p class="text-muted small mb-0">Administre os associados cadastrados na plataforma.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="<?= defined('URL_BASE') ? URL_BASE . '/cadastro' : '' ?>" class="btn btn-warning px-3 py-2 fw-bold shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Cadastrar Novo
            </a>
            <span class="badge bg-dark px-3 py-2">Nível <?= htmlspecialchars($user['user_tipo'] ?? '0') ?></span>
        </div>
    </div>

    <div class="row">

        <!-- Sidebar de Filtros -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-2">
                    <nav class="nav flex-column admin-nav">
                        <!-- Lógica para exibir o total ao lado de 'Todos' quando ativo -->
                        <a href="?status=geral&limit=<?= $pagination['items_per_page'] ?>" class="nav-link <?= (!isset($filter) || $filter == 'geral') ? 'active' : '' ?>">
                            <i class="fas fa-list me-2"></i> Todos
                            <?php if (!isset($filter) || $filter == 'geral'): ?>
                                <span class="opacity-50">&nbsp;- <?= $pagination['total_items'] ?> registros</span>
                            <?php endif; ?>
                        </a>
                        <a href="?status=ativos&limit=<?= $pagination['items_per_page'] ?>" class="nav-link <?= ($filter == 'ativos') ? 'active' : '' ?>">
                            <i class="fas fa-check-circle me-2"></i> Ativos
                                             <?php if (!isset($filter) || $filter == 'ativos'): ?>
                                <span class="opacity-50">&nbsp;- <?= $pagination['total_items'] ?> registros</span>
                            <?php endif; ?>
                        </a>
                        <a href="?status=inativos&limit=<?= $pagination['items_per_page'] ?>" class="nav-link <?= ($filter == 'inativos') ? 'active' : '' ?>">
                            <i class="fas fa-times-circle me-2"></i> Inativos
                                             <?php if (!isset($filter) || $filter == 'inativos'): ?>
                                <span class="opacity-50">&nbsp;- <?= $pagination['total_items'] ?> registros</span>
                            <?php endif; ?>
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- SELETOR DE QUANTIDADE POR PÁGINA -->
            <div class="card shadow-sm border-0 rounded-4 mt-3">
                <div class="card-body p-3">
                    <label class="form-label small fw-bold text-muted">Registros por página:</label>
                    <select class="form-select form-select-sm" onchange="location.href='?status=<?= $filter ?>&limit=' + this.value">
                        <?php foreach ([10, 25, 50, 80, 100, 200] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $pagination['items_per_page'] == $opt ? 'selected' : '' ?>><?= $opt ?> registros</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabela de Associados -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover admin-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-4">Associado</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Cadastro</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($associados)): ?>
                                    <?php foreach ($associados as $assoc): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center py-2">
                                                    <div class="avatar-wrapper me-3">
                                                        <?php $foto = !empty($assoc['foto']) ? $assoc['foto'] : 'sem-foto.png'; ?>
                                                        <img src="<?= defined('URL_BASE') ? URL_BASE . '/assets/foto/' . $foto : '' ?>" alt="" class="rounded-circle shadow-sm" width="45" height="45" style="object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= htmlspecialchars($assoc['nomever'] ?: $assoc['nome_completo']) ?></div>
                                                        <div class="text-muted extra-small"><?= htmlspecialchars($assoc['email']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-secondary border fw-normal"><?= htmlspecialchars($assoc['tipo_ass'] ?? 'Associado') ?></span>
                                            </td>
                                            <td>
                                                <?php if ($assoc['id_status'] == 1): ?>
                                                    <span class="status-badge status-active"><i class="fas fa-circle me-1"></i> Ativo</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1"></i> Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= date('d/m/Y', strtotime($assoc['data_cad'])) ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group btn-group-sm rounded-pill overflow-hidden border shadow-sm">
                                                    <a href="<?= defined('URL_BASE') ? URL_BASE . '/edit-profile?id=' . $assoc['id_associados'] : '' ?>" class="btn btn-white text-primary border-0" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-white text-<?= $assoc['id_status'] == 1 ? 'warning' : 'success' ?> border-0" onclick="toggleStatus(<?= $assoc['id_associados'] ?>, <?= $assoc['id_status'] ?>)" title="<?= $assoc['id_status'] == 1 ? 'Desativar' : 'Ativar' ?>">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white text-danger border-0" onclick="deleteAssociate(<?= $assoc['id_associados'] ?>)" title="Excluir">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                            <p>Nenhum associado encontrado.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Rodapé com Paginação -->
                <div class="card-footer bg-light border-top-0 py-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small">
                            Página <strong><?= $pagination['current'] ?></strong> de <strong><?= $pagination['total_pages'] ?></strong> (<strong><?= $pagination['total_items'] ?></strong> registros no total)
                        </div>

                        <?php if ($pagination['total_pages'] > 1): ?>
                            <nav aria-label="Paginação da tabela">
                                <ul class="pagination pagination-custom mb-0">
                                    <li class="page-item <?= ($pagination['current'] <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?status=<?= $filter ?>&limit=<?= $pagination['items_per_page'] ?>&page=<?= $pagination['current'] - 1 ?>" aria-label="Anterior">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    <?php 
                                    $range = 1; 
                                    for ($i = 1; $i <= $pagination['total_pages']; $i++): 
                                        if ($i == 1 || $i == $pagination['total_pages'] || ($i >= $pagination['current'] - $range && $i <= $pagination['current'] + $range)):
                                    ?>
                                        <li class="page-item <?= ($pagination['current'] == $i) ? 'active' : '' ?>">
                                            <a class="page-link" href="?status=<?= $filter ?>&limit=<?= $pagination['items_per_page'] ?>&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php 
                                        elseif ($i == $pagination['current'] - $range - 1 || $i == $pagination['current'] + $range + 1):
                                    ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php 
                                        endif;
                                    endfor; 
                                    ?>

                                    <li class="page-item <?= ($pagination['current'] >= $pagination['total_pages']) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?status=<?= $filter ?>&limit=<?= $pagination['items_per_page'] ?>&page=<?= $pagination['current'] + 1 ?>" aria-label="Próximo">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    const API_BASE = '<?= defined('URL_BASE') ? URL_BASE : '' ?>';

    function toggleStatus(id, currentStatus) {
        Swal.fire({
            title: 'Alterar status?',
            text: `Deseja ${currentStatus == 1 ? 'desativar' : 'ativar'} este associado?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, confirmar!',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#527d76',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('status', currentStatus);

                fetch(`${API_BASE}/admin/toggle-status`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Sucesso!', 'Status atualizado.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Erro', data.message || 'Erro ao alterar status', 'error');
                    }
                })
                .catch(err => console.error(err));
            }
        });
    }

    function deleteAssociate(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);

                fetch(`${API_BASE}/admin/delete`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Excluído!', 'O associado foi removido.', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Erro', data.message || 'Erro ao excluir', 'error');
                        }
                    });
            }
        })
    }
</script>