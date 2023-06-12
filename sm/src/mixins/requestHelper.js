export default {
    generateParamsForRequest(controller, params = []) {
        return [`module=SpamManager`, `c=${controller}`, `json=1`].concat(params).join("&");
    },
    OpenNewPage(baseurl, url)
    {
        window.open(
           baseurl +
              url,
            "_blank"
          );
    },
    OpenTplEdit(baseurl, tplid)
    {
        this.OpenNewPage(baseurl, 'configemailtemplates.php?action=edit&id='+tplid)
    }
}
