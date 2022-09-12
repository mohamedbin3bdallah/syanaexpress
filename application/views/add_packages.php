<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('add_package'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Admin/packageAction', $attributes); ?>
           <!--  <p class="card-description">
              Personal info
            </p> -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('title'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" name="title" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('description'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" name="description" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('price'); ?></label>
                  <div class="col-sm-9">
                    <input type="number" name="price" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("type"); ?></label>
                  <div class="col-sm-9">
                    <select class="form-control" name="type">
                      <option value="0"><?php echo get_phrase('free'); ?></option>
                      <option value="1"><?php echo get_phrase('monthly'); ?></option>
                      <option value="2"><?php echo get_phrase('quarterly'); ?></option>
                      <option value="3"><?php echo get_phrase('halfyearly'); ?></option>
                      <option value="4"><?php echo get_phrase('yearly'); ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">                         
                    <input class="btn btn-primary" type="submit" name="submit" value="submit" />
                </div>
              </div>
            </div>
            </form>
            </div>
         </div>
      </div>
    </div>
</div>