 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('edit_skills'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/editSkillsAction', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('skill_name'); ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="skill" value="<?php echo $skills->skill; ?>" class="form-control" required="" placeholder="Skill Name" />
                            <input type="hidden" name="id" value="<?php echo $skills->id ?>" class="form-control" required="" placeholder="Skill Name" />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('select_catgory'); ?></label>
                            <div class="col-sm-9">
                              <select class="form-control" name="cat_id">
                                <?php $cat_id= $skills->cat_id;
                                 foreach ($category as $category) { ?>
                                <option value="<?php echo $category->id ?>"<?php if ($cat_id == $category->id) echo ' selected="selected"'; ?>><?php echo $category->cat_name ?></option>
                                <?php } ?>
                              </select>
                        </div>
                      </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2"><?php echo get_phrase('submit'); ?></button>
                  </form>
                </div>
              </div>
            </div>
  </div>
</div>