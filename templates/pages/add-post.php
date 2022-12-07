<section class="form_section">
    <div class="container form_section-conainer">
        <h2>Add Post</h2>
        <div class="alert_message error">
            <p>This is an error message</p>
        </div>
        <form action="" enctype="multipart/form-data">
            <input type="text" placeholder="Title">
            <select>
                <option value="1">Travel</option>
                <option value="1">Art</option>
                <option value="1">Science & Technology</option>
                <option value="1">Travel</option>
                <option value="1">Travel</option>
                <option value="1">Travel</option>
            </select>
            <div class="form-control">
                <input type="checkbox" id="is_featured" checked>
                <label for="is_featured">Featured</label>
            </div>
            <div class="form_control">
                <label for="thumbnail">Add Thumbnail</label>
                <input type="file" id="thumbnail">
            </div>
            <textarea rows="4" placeholder="Description"></textarea>
            <button type="submit" class="btn">Add Post</button>
        
        </form>
    </div>
</section>