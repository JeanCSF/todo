<style>
    .modal-title {
        margin: 0 auto !important;
    }

</style>
<!-- Reply Modal -->
 <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5 text-center" id="replyModalLabel">Editar Resposta</h1>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="p-4 mb-3">
                             <form action="" id="formReply" method="post">
                                 <div class="row mb-3">
                                     <textarea style="height: 150px;" name="reply_content" id="reply_content" value="" class="form-control" required></textarea>
                                 </div>
                                 <input type="hidden" name="reply_id" id="reply_id" value="">
                         </div>
                         <div class="modal-footer d-flex justify-content-between">
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                             <input type="submit" value="Salvar" id="btnReply" onclick="" class="btn btn-success">
                         </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Reply Modal -->

 <!-- Profile Modal -->
 <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5 text-center" id="profileModalLabel">Foto do Perfil</h1>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="p-4 mb-3">
                             <form action="<?= url_to('upload') ?>" id="formProfilePic" method="post" enctype="multipart/form-data">
                                 <div class="form-group">
                                     <?php if (session()->has('errors')) : ?>
                                         <p class="text-danger"><?= session()->get('errors')['userfile'] ?></p>
                                     <?php endif; ?>
                                     <?php if (session()->has('uploaded')) : ?>
                                         <p class="text-success"><?= session()->get('uploaded') ?></p>
                                     <?php endif; ?>
                                     <input type="file" name="userfile" id="userfile" class="form-control-file">
                                 </div>
                                 <div class="modal-footer d-flex justify-content-between">
                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                     <input type="submit" value="Salvar Imagem" id="btnUpload" onclick="" class="btn btn-success">
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Profile Modal -->

 <!-- Task Modal -->
 <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5 text-center" id="taskModalLabel"></h1>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="p-4 mb-3">
                             <form action="" id="form" method="post">
                                 <div class="row mb-3">
                                     <input type="text" placeholder="Nome da tarefa" name="job_name" id="job_name" value="" class="form-control" autofocus required>
                                 </div>
                                 <div class="row mb-3">
                                     <textarea style="height: 150px;" name="job_desc" id="job_desc" value="" class="form-control" placeholder="Descrição" required></textarea>
                                 </div>
                                 <input type="hidden" name="id_job" id="id_job" value="">
                                 <input type="hidden" id="editar" value="">
                                 <div class="modal-footer d-flex justify-content-between">
                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                     <input type="submit" value="" id="btnSubmit" onclick="" class="btn btn-success">
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Task Modal -->

 <!-- Delete Modal -->
 <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="modalTitle"></h1>
             </div>
             <div class="modal-body text-center">
                 <h3 id="bodyMsg"></h3>
                 <h5 id="tarefa"></h5>
                 <span class="text-danger fw-bold">Esta ação é irreversível</span>
             </div>
             <div class="modal-footer d-flex justify-content-between">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-danger" id="btnDeletar" data-delete="">Sim, Deletar</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Delete Modal -->

 <!-- Privacy Modal -->
 <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Privacidade</h5>
             </div>
             <div class="modal-body p-4 mb-3">
                 <form action="<?= base_url('todocontroller/changeprivacy') ?>" id="formPrivacy" method="post">
                     <div class="row">
                         <div class="row mb-3 d-flex">
                             <div class="col-1">
                                 <input type="radio" name="privacyRb" id="privacyRb" value="<?= true ?>">
                             </div>
                             <div class="col-11">
                                 <label for="privacyRb">Visível para todos</label>
                             </div>
                         </div>
                         <div class="row mb-3 d-flex">
                             <div class="col-1">
                                 <input type="radio" name="privacyRb" id="privacyRb" value="<?= false ?>">
                             </div>
                             <div class="col-11">
                                 <label for="privacyRb">Somente eu</label>
                             </div>
                         </div>
                     </div>
                     <input type="hidden" name="privacy_id" id="privacy_id" value="">
                     <div class="modal-footer d-flex justify-content-between">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                         <input type="submit" class="btn btn-primary" id="btnPrivacy" value="Salvar Alterações">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- Privacy Modal -->

 <!-- Plus Task Modal -->
 <div class="modal fade" id="plusTaskModal" tabindex="-1" aria-labelledby="plusTaskModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="plusTaskModalTitle"></h5>
             </div>
             <div class="modal-body col-10 offset-1 p-2">
                 <div class="row">
                     <p id="plusTaskModalDesc" class="text-justify"></p>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Plus Task Modal -->

 <!-- Likes Modal -->
 <div class="modal fade" id="likesModal" tabindex="-1" aria-labelledby="likesModallLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Curtidas</h5>
             </div>
             <div class="modal-body p-2">
                 <div id="likesModalContainer">
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Likes Modal -->

 <!-- Profile Views Modal -->
 <div class="modal fade" id="profileViewsModal" tabindex="-1" aria-labelledby="profileViewsModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Visitantes</h5>
             </div>
             <div class="modal-body p-2">
                 <div id="profileViewsModalContainer">
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Profile Views Modal -->

 <!-- Toast Notification -->
 <div class="toast-container position-fixed bottom-0 end-0 p-3" style="top: 10px; right: 10px; z-index: 9999;">
     <div id="basicToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
         <div class="alert" style="margin-bottom: 0;" id="alerta">
             <span id="msgInfo" style="text-transform: capitalize;"></span>
             <button type="button" class="btn-close btn-close-black float-end" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
     </div>
 </div>
 <!-- Toast Notification -->

  <!-- Coming Soon Modal -->
  <div class="modal fade" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <!-- <div class="modal-header">
                 <h1 class="modal-title fs-5">Em Breve!</h1>
             </div> -->
             <div class="modal-body text-center">
                 <span class="text-warning fw-bold">Este botão não faz nada ainda, estamos trabalhando nisso!</span><br>
                 <span class="fs-1 fw-bold">😁</span>
             </div>
             <!-- <div class="modal-footer d-flex justify-content-between">
             </div> -->
         </div>
     </div>
 </div>
 <!-- Coming Soon Modal -->