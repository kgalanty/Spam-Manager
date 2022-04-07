export default {
    generateParamsForRequest(controller, params = []) {
        return [`module=SpamManager`, `c=${controller}`, `json=1`].concat(params).join("&");
    },
    OpenNewPage(url)
    {
        window.open(
            this.$baseurl +
              url,
            "_blank"
          );
    },
    OpenTplEdit(id)
    {
        this.OpenNewPage('configemailtemplates.php?action=edit&id='+id)
    }
}
