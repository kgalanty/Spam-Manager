export default {
    extractGroupTplName(name) {
        if(!name) return null
        const splitted = name.split("_", 3);
        return splitted[1] + " -> " + splitted[2];
      },
}
